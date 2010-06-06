<?php

require '../core/conf.php';
require $path . '/core/func.php';

$sql = "select * from " . DB_PREFIX . "thumbs where custom=0";
$result = $db->query( $sql );

while ( $thumb = $result->fetch_assoc() ) {
	$image = imagecreatefromstring( $thumb['data'] );
	$width = imagesx($image);
	$height = imagesy($image);
	$ratio = $width / $height;
	$out_height = 100;
	$out_width = 100;

	if ( $out_width / $out_height > $ratio )
		$out_width = ceil( 100 * $ratio );
	else
		$out_height = ceil( 100 / $ratio );

	$ithumb = imagecreatetruecolor( $out_width, $out_height );
	imagecopyresampled( $ithumb, $image, 0, 0, 0, 0,
						$out_width, $out_height,
						$width, $height );

	ob_start();
		imagejpeg( $ithumb, '', 100 );
		$output = ob_get_contents();
	ob_end_clean();

	imagedestroy( $image );
	imagedestroy( $ithumb );

	$size = strlen( $output );
	$sql = sprintf("update " . DB_PREFIX . "thumbs set size=%d,data='%s' where id=%d", $size, $db->real_escape_string( $output ), $thumb['id']);
	$db->query( $sql );
}

?>
