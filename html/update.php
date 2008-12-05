<?php


require '../core/conf.php';

if ( $_POST ) {
	$title = strval( $_POST['title'] );
	$id = intval( $_GET['id'] );
	
	$sql = sprintf("select password from entries where id=%d", $id );
	$result = $db->query( $sql );
	$entry = $result->fetch_assoc();

	if ( $entry['password'] == $db->real_escape_string( strval( $_POST['password'] ) ) ) {

	if ( $_POST['tags'] ) {
		$tags = explode( ',', trim( strval( $_POST['tags'] ) ) );
		$tag_count = count( $tags );
	} else {
		$tags[] = 'none';
		$tag_count = 1;
	}

	$sql = sprintf( "update entries set title='%s' where id=%d", $db->real_escape_string( $title ), $id );
	if ( !$db->query( $sql ) )
		die('error in query');

	$sql = sprintf( "delete from tagmap where entry=%d", $id );
	if ( !$db->query( $sql ) )
		die('error in query');

	for ( $i = 0; $i < $tag_count; $i++ ) {
		$cur = strtolower( $tags[$i] );
		$sql = sprintf( "select id from tags where name='%s'", $cur );
		$result = $db->query( $sql );
		if ( $result->num_rows < 1 ) {
			$sql = sprintf( "insert into tags (name) values ('%s')", $cur );
			$db->query( $sql );
			$tag_id = $db->insert_id;
		} else {
			$row = $result->fetch_array();
			$tag_id = $row[0];
		}
		$sql = sprintf( "insert into tagmap (tag,entry) values (%d,%d)", $tag_id, $id );
		$db->query( $sql );
	}

	} else {
		header("location: $loc/edit/" . $id . "/");
	}
}

header("location: $loc/view/" . $id . "/");

$db->close();


?>
