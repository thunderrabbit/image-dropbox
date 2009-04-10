<?php

if ( $_POST ) {
	$title = strval( $_POST['title'] );
	$id = intval( $entry );
	$host = gethostbyaddr( $_SERVER['REMOTE_ADDR'] ) . ' (' . $_SERVER['REMOTE_ADDR'] . ')';
	
	$sql = sprintf("select password,title,safe from entries where id=%d", $id );
	$result = $db->query( $sql );
	$entry = $result->fetch_assoc();
	$safe = intval( $_POST['rating'] );

	if ( $entry['password'] == $db->real_escape_string( strval( $_POST['password'] ) ) ) {

	if ( $_POST['tags'] ) {
		$tags = explode( ',', str_replace( ' ', '_', trim( strval( $_POST['tags'] ) ) ) );
		sort( $tags, SORT_STRING );
		$tag_count = count( $tags );
	} else {
		$tags[] = 'none';
		$tag_count = 1;
	}

	$sql = sprintf( "update entries set title='%s',safe=%d where id=%d", $db->real_escape_string( $title ), $safe, $id );
	if ( !$db->query( $sql ) )
		die('error in query '.$sql);

	if ( $title != $entry['title'] ) {
		$sql = sprintf( "insert into updates (entry,ip,date,field,`from`,`to`) values (%d,'%s',%d,'%s','%s','%s')",
						$id,$host,time(),'title',$entry['title'],$title );
		if ( !$db->query( $sql ) )
			die('error in query '.$sql);
	}
	if ( $safe != $entry['safe'] ) {
		$oldrating = ($entry['safe'] == 1) ? 'Yes' : 'No';
		$newrating = ($safe == 1) ? 'Yes' : 'No';
		$sql = sprintf( "insert into updates (entry,ip,date,field,`from`,`to`) values (%d,'%s',%d,'%s','%s','%s')",
						$id,$host,time(),'worksafe',$oldrating,$newrating );
		if ( !$db->query( $sql ) )
			die('error in query '.$sql);
	}

	$sql = sprintf( "select t.name from tags t, tagmap m where m.entry=%d && t.id=m.tag order by t.name", $id );
	$result = $db->query( $sql );
	for($i = 0; $row = $result->fetch_assoc(); $i++ ) {
		if ( $i > 0 ) $oldtags .=',';
		$oldtags .= $row['name'];
	}
	$newtags = implode( ',', $tags );

	if ( $newtags != $oldtags ) {

		$sql = sprintf( "delete from tagmap where entry=%d", $id );
		if ( !$db->query( $sql ) )
			die('error in query '.$sql);

		for ( $i = 0; $i < $tag_count; $i++ ) {
			$cur = trim( str_replace( ' ', '_', strtolower( $tags[$i] ) ) );
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

		$sql = sprintf( "insert into updates (entry,ip,date,field,`from`,`to`) values (%d,'%s',%d,'%s','%s','%s')",
						$id,$host,time(),'tags',$oldtags,$newtags );
		if ( !$db->query( $sql ) )
			die('error in query '.$sql);

	}

	} else {
		header("location: $loc/edit/" . $id . "/");
	}
}

header("location: $loc/view/" . $id . "/");

$db->close();

?>
