<?php

require '../core/conf.php';
require $path . '/core/func.php';

$sql = "select * from " . DB_PREFIX . "thumbs where size=0";
$result = $db->query( $sql );

while ( $thumb = $result->fetch_assoc() ) {
	$size = strlen( $thumb['data'] );
	$sql = sprintf("update " . DB_PREFIX . "thumbs set size=%d where id=%d", $size, $thumb['id']);
	$db->query( $sql );
	print "$sql<br/>";
}

?>
