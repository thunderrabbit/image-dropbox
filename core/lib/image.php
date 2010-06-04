<?php

// Image management class, deals with image manipulations for the creation of
// thumbnails and previews as well as inserting new entries into the database.

define('CROP_TOP', 1 );
define('CROP_MIDDLE', 2 );
define('CROP_BOTTOM', 3 );

require_once DB_PATH . '/core/lib/entry.php';

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
	private $custom;
	private $custom_crop;
	private $custom_size;
	private $custom_mode;	

	public function __construct($db) { 
		$this->db = $db;	
		$this->block = 65535;
		$this->custom_mode = CROP_MIDDLE;
	}

	public function __destruct() { 
		if ( $this->image ) {
			imagedestroy( $this->image );	
		}
	}

	public function getentryid() {
		return $this->entryid;
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

	public function checkpost( $authenticated = false ) {
		if ( $_POST && $_FILES['image'] ) {
			if ( is_uploaded_file( $_FILES['image']['tmp_name'] ) ) {
				$this->file = $_FILES['image']['tmp_name'];
				if ( $_POST['title'] ) {
					$this->title = $this->db->safe( $_POST['title'] );
				} else {
					throw new ImageException(DB_STR_IMGERR_NOTITLE);
				}
				if ( $img = getimagesize( $this->file ) ) {
					if ( in_array( $img[2], array( 1, 2, 3 ) ) ) {
						$this->type = $img[2];
					}
					$this->width = $img[0];
					$this->height = $img[1];
				} else {
					throw new ImageException(DB_STR_IMGERR_UNSUPPORTED);
				}
				$this->size = filesize( $this->file );
				$this->hash = sha1_file( $this->file );
				// If there is an authenticated user set the password to nothing even if something
				// exists in the form data
				$this->password = ( $authenticated ) ? '' : sha1( $_POST['password'] );
				$this->host = $this->db->safe( gethostbyaddr( $_SERVER['REMOTE_ADDR'] ) 
								. ' (' . $_SERVER['REMOTE_ADDR'] . ')' );
				$this->worksafe = ( intval( $_POST['rating'] ) ) ? 1 : 0;
				if ( $_POST['tags'] ) {
					$this->tags = explode( ',', strval( $_POST['tags'] ) );
				} else {
					$this->tags[] = 'none';
				}

				$this->custom = ( intval( $_POST['custom'] ) == 1 );
				if ( $this->custom ) {
					$this->custom_size = intval( $_POST['size'] );
					$this->custom_crop = ( intval( $_POST['square'] ) );
					$mode = intval( $_POST['crop'] );
					if ( $mode >= CROP_TOP && $mode <= CROP_BOTTOM ) { 
						$this->custom_mode = $mode;
					}
				}

			} else {
				throw new ImageException(DB_STR_IMGERR_NOFILE);
			}
		} else {
			throw new ImageException('no post data');
		}
	}

	public function checkduplicate() {
		if ( $rel = $this->db->query('SELECT id FROM entries WHERE hash=\'' 
					. $this->hash . '\'' ) ) {
			$dup = ($rel->num_rows < 1);
			$rel->free();
			if ( ! $dup ) {
				throw new ImageException(DB_STR_IMGERR_DUP);
			}
		} else {
			throw new ImageException(DB_STR_IMGERR_APPDUP);
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
			throw new ImageException(DB_STR_IMGERR_APPTHUMB);
		}
		if ( !imagecopyresampled( $image, $this->image, 0, 0, 0, 0,
							$out[0], $out[1],
							$this->width, $this->height ) ) {
			throw new ImageException(DB_STR_IMGERR_APPTHUMB);
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
	
	public function createentry($authenticated=false) {
		try {
			$entry = new Entry($this->db);
			$entry->update('title', $this->title);
			$entry->update('type', $this->type);
			$entry->update('size', $this->size);
			$entry->update('width', $this->width);
			$entry->update('height', $this->height);
			$entry->update('ip', $this->host);
			$entry->update('password', $this->password);
			$entry->update('safe', $this->worksafe);
			$entry->update('hash', $this->hash);
			if($authenticated)
				$entry->update('user', $_SESSION['auth_id']);
			$entry->save();
			$this->entryid = $entry->get_id();
			return true;
		} catch(Exception $e) {
			throw new ImageException('application error while creating entry',true);
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
					throw new ImageException(DB_STR_IMGERR_APPCUSTTHUMB,true);
				}
			} else {
				return true;
			}
		} else {
			if( !$resize = $this->resize( array(100,100) ) ) {
				throw new ImageException(DB_STR_IMGERR_APPTHUMB,true);
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
			throw new ImageException(DB_STR_IMGERR_APPTHUMB,true);
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
				throw new ImageException(DB_STR_IMGERR_APPWRITE,true);
			}
		}

		fclose( $fp );

		return true;
	}

	public function createtags() {
		try {
			$tags = new Tags($this->db, $this->entryid);
			$tags->update($this->tags);
			$tags->save(false);
			return true;
		} catch(Exception $e) {
			throw new ImageException('application error while setting tags',true);
		}
	}

	public function loadimage() {
		if ($this->image = imagecreatefromstring( file_get_contents( $this->file ) )) {
			return true;
		} else {
			throw new ImageException(DB_STR_IMGERR_APPREAD, true);
		}
	}
	
	public function upload($authenticated) {
		$this->checkpost($authenticated);
		$this->checkduplicate();
		$this->start();
		$this->createentry($authenticated);
		$this->createtags();
		$this->loadimage();
		$this->createthumb();
		$this->createthumb(1);
		$this->createdata();
		$this->createpreview();
		$this->commit();
	}
}
?>
