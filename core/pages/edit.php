<?php

$id = intval( $entry );

$sql = sprintf( "select title,safe,user from " . DB_PREFIX . "entries where id=%d", $id );

if ( ! $result = $db->query( $sql ) ) {
	die("Query Error");
}

$entry = $result->fetch_assoc();
$title = $entry['title'];
$rating = $entry['safe'];
$pass = true;

$sql = sprintf( "select t.name from " . DB_PREFIX . "tags t, " . DB_PREFIX . "tagmap m where m.entry=%d && t.id=m.tag", $id );

$info = $result->fetch_assoc();

if ( ! $result = $db->query( $sql ) ) {
	die( "Query Error" );
}

$tags = '';
for($i = 0; $row = $result->fetch_assoc(); ++$i ) {
	$tags .= ( $i > 0 ) ? ',' . str_replace('_',' ',$row['name']) : str_replace('_',' ',$row['name']);
}

if($authenticated && $_SESSION['auth_id'] == $entry['user'])
	$pass = false;

include DB_PATH . '/core/themes/' . DB_THEME . '/edit.php';
?>
