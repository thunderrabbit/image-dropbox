<?php

require $path . '/core/lib/image.php';

$img = new image( $db );

function error( $img, $rollback, $msg ) {
	global $url, $loc;
	if ( $rollback ) {
		$img->rollback();
	}
	$_SESSION['upload_errors'] = $img->geterrors();
	header('Location: http://' . $url . $loc . '/upload/');
	exit();
}

if ( !$img->checkpost() ) error( $img, false, 'checkpost' );
if ( !$img->checkduplicate() ) error( $img, false, 'checkduplicate' );
$img->start();
if ( !$img->createentry() ) error( $img, true, 'createentry' );
if ( !$img->createtags() ) error( $img, true, 'createtags' );
if ( !$img->loadimage() ) error( $img, true, 'loadimage' );
if ( !$img->createthumb() ) error( $img, true, 'createthumb' );
if ( !$img->createthumb(1) ) error( $img, true, 'createthumb-custom' );
if ( !$img->createdata() ) error( $img, true, 'createdata' );
if ( !$img->createpreview() ) error( $img, true, 'createpreview' );
$img->commit();

header('Location: http://' . $url . $loc . '/view/' . $img->getentryid() );

trigger_error('submit completed');

$db->close();
exit();

?>
