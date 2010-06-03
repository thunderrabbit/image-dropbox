<?php

$id = intval( $entry );
$_SESSION['verify.' . $id ] = sha1(time().$id);

$sql = sprintf( "select title,width,height,size,date,views,ip,safe,hash,child,type,user from entries where id=%d", $id );

if ( ! $result = $db->query( $sql ) ) {
	die("Query Error");
}


$entry = $result->fetch_assoc();

if ( $entry['width'] > 800 ) {
	$ratio = $entry['width'] / $entry['height'];
	$width = 800;
	$height = $width / $ratio;
} else {
	$width = $entry['width'];
	$height = $entry['height'];
}

// check for custom thumbnail
$sql = sprintf( "select id from thumbs where entry=%d && custom=1", $id );
$result = $db->query($sql);
$custom = ( $result->num_rows == 1 );
$display_id = ($entry['child']) ? $entry['child'] : $id;

$filename = $id . '.' . imgtypetoext( $entry['type'] );

if ( $entry['user'] > 0 ) {
	$sql = sprintf('select alias,username from users where id=%d', $entry['user']);
	if ( $info = $db->select( $sql ) ) {
		$user= '<a href="http://' . DB_URL . DB_LOC . '/user/' . $info['username'] . '/">' . $info['alias'] . '</a>';
	}
} else {
	$user = $entry['ip'];
}

$sql = sprintf( "select t.name from tags t, tagmap m where m.entry=%d && t.id=m.tag", $id );
if (!$tags = $db->query( $sql ) ) {
	die( "Query Error" );
}

include DB_PATH . '/core/themes/' . DB_THEME . '/view.php';

?>
