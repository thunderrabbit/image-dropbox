<?php

require '../core/conf.php';
require $path . '/core/func.php';

$sql = "select id,thumb from " . DB_PREFIX . "entries where child is null";
$result = $db->query( $sql );

$sql = "insert into " . DB_PREFIX . "thumbs (entry,data) values ";
$first = 0;
while( $row = $result->fetch_assoc() ) {
	$comma = ($first++ == 0) ? '' : ',';
	$sql .= sprintf(" %s (%d,(SELECT thumb FROM " . DB_PREFIX . "entries where id=%d)) ", $comma, $row['id'], $row['id'] );
}

$db->query( $sql );

?>
