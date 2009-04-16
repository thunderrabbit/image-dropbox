<?php

//$ts = microtime(time);

require '../core/conf.php';
require $path . '/core/func.php';

$mode = ( $_GET['mode'] == 'thumb' ) ? 'thumb' : 'image';
$id = intval( $_GET['id'] );


$sql = 'SELECT date,size,parent,type FROM entries WHERE id=' . $id;
$result = $db->query( $sql );
$row = $result->fetch_assoc();
$date = $row['date'];
$size = $row['size'];
$type = $row['type'];
$parent_id = ($row['parent']) ? $row['parent'] : $id;

$ar = apache_request_headers();

if ( isset( $ar['If-Modified-Since'] ) &&
	( $ar['If-Modified-Since'] != '' ) &&
	( strtotime( $ar['If-Modified-Since']) >= $date ) ) {
	header( 'Last-Modified: ' . gmdate('D, d M Y H:i:s' ) . ' GMT', true, 304 );
//	trigger_error( "image processed in " . ( microtime(true) - $ts ) . " seconds" );
	exit();
} else {
	header('Last-Modified: ' . gmdate( 'D, d M Y H:i:s', $date ) . ' GMT', true, 200 );
	header('Expires: ' . gmdate( 'D, d M Y H:i:s',  time() + 86400 ) . ' GMT', true, 200 );
}

$cacheid = sha1( $id . $mode );
if ( checkcache( $cacheid ) ) {
	echo file_get_contents( $path . '/cache/' . $cacheid );	
} else {

if ( $mode == 'thumb' )
{
	$sql = 'SELECT data,size FROM thumbs WHERE entry=' . $id . ' && custom=' .
		( ( $_GET['args'] && substr( $_GET['args'], 0, 6 ) == 'custom' ) ? 1 : 0 );

	if ( $result = $db->query( $sql ) ) {
		$row = $result->fetch_assoc();
		header( 'Content-Length: ' . $row['size'] );
		header( "content-type: image/jpeg" );
		echo $row['data'];
		file_put_contents( $path . '/cache/' . $cacheid, $row['data'] );
	}

} else {
	$sql = sprintf( "UPDATE entries SET views=views+1 WHERE id=%d", $parent_id );
	if ( !$db->query( $sql ) ) die( "Query Error" );
	$sql = sprintf( "SELECT id FROM data WHERE entryid=%d order by id", $id );
	$result = $db->query( $sql );
	header('Content-Length: ' . $size );
	header("content-type: " . imgtypetomime($type) );
	while ( $row = $result->fetch_array() )
		$chunks[] = $row[0];
	$size = count( $chunks );
	for( $i = 0; $i < $size; ++$i ) {
		$sql = sprintf( "select filedata from data where id=%d", $chunks[$i] );
		$result = $db->query( $sql );
		$row = $result->fetch_array();
		echo $row[0];
	}
}

}

$db->close();

//trigger_error( "image processed in " . ( microtime(true) - $ts ) . " seconds" );

?>
