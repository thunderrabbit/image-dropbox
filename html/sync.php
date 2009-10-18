<?php
// read in files from DB_DATAPATH

require '../core/conf.php';
require $path . '/core/lib/image.php';

function error( $img, $rollback, $msg ) {
	if( $rollback ) {
		$img->rollback();
	}

	print '<p>ERROR: ' . $msg . ' ' . implode(' ',$img->geterrors()) . '</p>';
}

function ls( $path, $sub, &$files ) {
	$dir = opendir( $path . $sub );
	while( false !== ( $file = readdir( $dir ) ) ) {
		if ( $file != '.' && $file != '..' ) {
			if ( is_dir( $path . $sub . $file . '/' ) ) {
				ls( $path, $sub . $file . '/', $files );			
			} else {
				$files[] = $sub . $file;
			}	
		}
	}
}

function checkduplicate($path) {
	global $db;
	print "\nchecking if $path exists...\n";
	if ( $rel = $db->query('SELECT id FROM entries WHERE path=\'' 
				. $path . '\'' ) ) {
		if ( $rel->num_rows != 0 ) {
			$rel->free();
			return true;
		} else {
			$rel->free();
			return false;
		}
	} else {
		return false;
	} 
}


$files = array();
ls( DB_DATAPATH, '', $files );

print '<pre>';

foreach( $files as $file ) {
	$tags = explode( '/', dirname( $file ) );
	$name = basename( $file );
	print $name;
	$_POST = array();

	if ( checkduplicate( DB_DATAPATH . $file ) ) {
		print " already exists...\n";
		flush();
		continue;
	}
	
	$_POST['title'] = $name;
	$_POST['file'] = $file;
	$_POST['tags'] = implode(',',$tags);
	$_POST['rating'] = 1;
	$_POST['custom'] = 0;
	$_POST['size'] = 100;
	$_POST['square'] = 0;
	$_POST['crop'] = 1;
	$_POST['password']  =  '';
	$img = new image( $db, $_POST );

	if ( !$img->checkpost( $authenticated ) ) {
		error( $img, false, 'checkpost' );
		continue;
	}
	if ( !$img->checkduplicate() )  {
		error( $img, false, 'checkduplicate' );
		continue;
	}
	$img->start();
	if ( !$img->createentry( $authenticated ) ) {
		error( $img, true, 'createentry' );
		continue;
	}
	if ( !$img->createtags() ) {
		error( $img, true, 'createtags' );
		continue;
	}
	if ( !$img->loadimage() ) {
		error( $img, true, 'loadimage' );
		continue;
	}
	if ( !$img->createthumb() ) {
		error( $img, true, 'createthumb' );
		continue;
	}
	if ( !$img->createthumb(1) ) {
		error( $img, true, 'createthumb-custom' );
		continue;
	}
	if ( !$img->createdata() ) {
		error( $img, true, 'createdata' );
		continue;
	}
	if ( !$img->createpreview() ) {
		error( $img, true, 'createpreview' );
		continue;
	}
	$img->commit();

	print " added...\n";
	flush();
}

print '</pre>';

$db->close();
exit();

?>
