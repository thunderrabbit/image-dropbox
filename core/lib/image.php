<?php

// Image management class, deals with image manipulations for the creation of
// thumbnails and previews as well as inserting new entries into the database.

define('CROP_TOP', 1 );
define('CROP_MIDDLE', 2 );
define('CROP_BOTTOM', 3 );

class image {
	private $db;
	private $size;
	private $hash;
	private $password;
	private $host;
	private $width;
	private $height;
	private $type;
	private $title;
	private $tags;
	private $entryid;
	private $image;
	private $file;
	private $block;
	private $errors;
	private $custom;
	private $custom_crop;
	private $custom_size;
	private $custom_mode;	

	public function __construct($db) { 
		$this->db = $db;	
		$this->block = 65535;
		$this->custom_mode = CROP_MIDDLE;
		$this->errors = array();
	}

	public function __destruct() { 
		if ( $this->image ) {
			imagedestroy( $this->image );	
		}
	}
	
	public function getentryid() {
		return $this->entryid;
	}

	public function geterrors() {
		return $this->errors;
	}

	public function seterror($error) {
		$this->errors[] = ucwords( $error );
	}

	public function start() {
		$this->db->query('START TRANSACTION');
	}

	public function commit() {
		$this->db->query('COMMIT');
	}

	public function rollback() {
		$this->db->query('ROLLBACK');
	}

	public function checkpost() {
		if ( $_POST && $_FILES['image'] ) {
			if ( is_uploaded_file( $_FILES['image']['tmp_name'] ) ) {
				$this->file = $_FILES['image']['tmp_name'];
				if ( $_POST['title'] ) {
					$this->title = $this->db->real_escape_string( strval( $_POST['title'] ) );
				} else {
					$this->seterror('Missing Title');	
					return false;
				}
				if ( $img = getimagesize( $this->file ) ) {
					if ( in_array( $img[2], array( 1, 2, 3 ) ) ) {
						$this->type = $img[2];
					}
					$this->width = $img[0];
					$this->height = $img[1];
				} else {
					$this->seterror('Unsupported Image');
					return false;
				}
				$this->size = filesize( $this->file );
				$this->hash = sha1_file( $this->file );
				$this->password = sha1( $_POST['password'] );
				$this->host = $this->db->real_escape_string( gethostbyaddr( $_SERVER['REMOTE_ADDR'] ) 
								. ' (' . $_SERVER['REMOTE_ADDR'] . ')' );
				$this->worksafe = (intval($_POST['rating'])) ? 1 : 0;
				if ( $_POST['tags'] ) {
					$this->tags = explode( ',', strval( $_POST['tags'] ) );
				} else {
					$this->tags[] = 'none';
				}

				$this->custom = (intval($_POST['custom']) == 1);
				if ( $this->custom ) {
					$this->custom_size = intval( $_POST['size'] );
					$this->custom_crop = ( intval( $_POST['square'] ) );
					$mode = intval( $_POST['crop'] );
					if ( $mode >= CROP_TOP && $mode <= CROP_BOTTOM ) { 
						$this->custom_mode = $mode;
					}
				}

			} else {
				$this->seterror('no uploaded file');
				return false;
			}
		} else {
			$this->seterror('no post data');
			return false;
		}
		return true;
	}

	public function checkduplicate() {
		if ( $rel = $this->db->query('SELECT id FROM entries WHERE hash=\'' 
					. $this->hash . '\'' ) ) {
			$dup = ($rel->num_rows < 1);
			$rel->free();
			if ( $dup ) {
				return true;
			} else {
				$this->seterror('duplicate image');
				return false;
			}
		} else {
			$this->seterror('application error while checking for duplicate image');
			return false;
		}
	}

	private function resize( $target, $crop=false, $mode=CROP_MIDDLE ) {
		$out = $target;

		if ( $crop ) {
			if ( $this->width > $this->height ) {
				$out[0] = ceil( $this->custom_size * ( $this->width / $this->height ) );
				$out[1] = $this->custom_size;
			} else {
				$out[0] = $this->custom_size;
				$out[1] = ceil( $this->custom_size * ( $this->height / $this->width ) );
			}
		} else {
			$ratio = $this->width / $this->height;
			if ( $out[0] / $out[1] > $ratio ) {
				$out[0] = ceil( $target[1] * $ratio );
			} else {
				$out[1] = ceil( $target[0] / $ratio );
			}
		}

		if ( !$image = imagecreatetruecolor( $out[0], $out[1] ) ) {
			$this->seterror('application error while creating thumbmail');
			return false;
		}
		if ( !imagecopyresampled( $image, $this->image, 0, 0, 0, 0,
							$out[0], $out[1],
							$this->width, $this->height ) ) {
			$this->seterror('application error while creating thumbmail');
			return false;
		}

		if ( $crop ) {
			$xy = $this->cropgetxy( $out, $target, $mode );
			$temp_image = imagecreatetruecolor( $target[0], $target[1] );
			imagecopyresampled( $temp_image, $image, 0, 0, $xy[0], $xy[1],
								$target[0], $target[1],
								$target[0], $target[1] );
			imagedestroy( $image );
			$image = $temp_image;
		}

		ob_start();
			imagejpeg( $image, '', 100 );
			imagedestroy( $image );
			$output = ob_get_contents();
		ob_end_clean();

		return array( $out, $output );
	}

	private function cropgetxy( $in, $out, $mode ) {
		switch ( $mode ) {
			case CROP_TOP:
				$xy = array(0,0);
				break;
			case CROP_BOTTOM:
				if ( $in[0] > $in[1] )
					$xy = array( ( $in[0] - $in[1] ), 0 );
				else
					$xy = array( 0, ( $in[1] - $in[0] ) );
				break;
			case CROP_MIDDLE:
			default:
				if ( $in[0] > $in[1] )
					$xy = array( ceil( ( $in[0] - $in[1] ) / 2), 0 );
				else
					$xy = array( 0, ceil( ( $in[1] - $in[0]) / 2) );
				break;
		}

		return $xy;
	}
	
	public function createentry() {
		$sql = sprintf( 'INSERT INTO `entries` (`title`,`type`,`size`,`width`,`height`,
						 `ip`,`password`,`date`,`safe`,`hash`,`views`) VALUES
						 (\'%s\',%d,%d,%d,%d,\'%s\',\'%s\',UNIX_TIMESTAMP(),%d,\'%s\',0)',
						 $this->title,
						 $this->type,
						 $this->size,
						 $this->width,
						 $this->height,
						 $this->host,
						 $this->password,
						 $this->worksafe,
						 $this->hash );
		if ( $this->db->query( $sql ) ) {
			$this->entryid = $this->db->insert_id;
			return true;
		} else {
			$this->seterror('application error while creating entry');
			return false;
		}
	}

	public function createpreview() {
		return true;
	}

	public function createthumb( $custom=0 ) {
		if ( $custom ) {
			if ( $this->custom ) {
				if ( !$resize = $this->resize( array( $this->custom_size, $this->custom_size ),
												$this->custom_crop, $this->custom_mode ) ) {
					$this->seterror('application error while create custom thumbnail');
					return false;
				}
			} else {
				return true;
			}
		} else {
			if( !$resize = $this->resize( array(100,100) ) ) {
				$this->seterror('application error while creating thumbnail');
				return false;
			}
		}
		$data = $resize[1];

		$sql = sprintf( 'INSERT INTO `thumbs` (`data`,`entry`,`custom`,`size`)
						 VALUES (\'%s\',%d,%d,%d)',
						 $this->db->real_escape_string( $data ),
						 $this->entryid,
						 $custom,
						 strlen( $data ) );
		if ( $this->db->query( $sql ) ) {
			return true;
		} else {
			$this->seterror('application error while creating thumbmail');
			return false;
		}
	}

	public function createdata() {
		$fp = fopen( $this->file, 'rb' );

		while( !feof( $fp ) ) {
			$sql = sprintf('INSERT INTO `data` (`entryid`,`filedata`) VALUES
							(%d,\'%s\')', $this->entryid,
							$this->db->real_escape_string( 
								fread( $fp, $this->block ) ) );
			if ( !$this->db->query( $sql ) ) {
				$this->seterror('application error while writing image');
				return false;
			}
		}

		fclose( $fp );

		return true;
	}

	public function createtags() {
		for ( $i = 0, $c = count( $this->tags ); $i < $c; ++$i ) {
			$current = $this->db->real_escape_string( str_replace( ' ', '_', 
						strtolower( trim( $this->tags[$i] ) ) ) );
			$sql = sprintf( 'SELECT `id` FROM `tags` WHERE `name` = \'%s\'',
							$current );
			if ( $result = $this->db->query( $sql ) ) {
				$exists = ( $result->num_rows > 0 );
			} else {
				$this->seterror('application error while setting tags');
				return false;
			}

			if ( $exists ) {
				$row = $result->fetch_assoc();
				$tag_id = $row['id'];
				$sql = sprintf( 'UPDATE `tags` SET `date` = UNIX_TIMESTAMP() 
								WHERE `id`=%d', $tag_id );
				if ( !$this->db->query( $sql ) ) {
					$this->seterror('application error while setting tags');
					return false;
				}
			} else {
				$sql = sprintf( 'INSERT INTO `tags` (`name`,`date`) VALUES
								(\'%s\',UNIX_TIMESTAMP())', $current );
				if ( !$this->db->query( $sql ) ) {
					$this->seterror('application error while setting tags');
					return false;
				} else {
					$tag_id = $this->db->insert_id;
				}
			}
			
			$result->free();

			$sql = sprintf( 'INSERT INTO `tagmap` (`tag`,`entry`) VALUES (%d,%d)',
							$tag_id, $this->entryid );
			if ( !$this->db->query( $sql ) ) {
				$this->seterror('application error while setting tags');
				return false;
			}
		}
		return true;
	}

	public function loadimage() {
		if ($this->image = imagecreatefromstring( file_get_contents( $this->file ) )) {
			return true;
		} else {
			$this->seterror('application error while reading uploaded image data');
			return false;
		}
	}

}
?>
