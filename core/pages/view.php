<?php


$id = intval( $entry );

$sql = sprintf( "select title,width,height,size,date,views,ip from entries where id=%d", $id );

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


$sql = sprintf( "select t.name from tags t, tagmap m where m.entry=%d && t.id=m.tag", $id );

$info = $result->fetch_assoc();

if ( ! $result = $db->query( $sql ) ) {
	die( "Query Error" );
}

?>
	<div id="content">
	<h2>View</h2>
	URL: <?=$url;?><?=$loc;?>/view/<?=$id;?>/
	<br/>
	Direct: <?=$url;?><?=$loc;?>/image/<?=$id;?>/
	<br/>
	Title: <?=$entry['title'];?>
	<br/>
	Tags: 
	<?
	for($i = 0; $row = $result->fetch_assoc(); $i++ ) {
		if ( $i > 0 ) print ', ';
		print '<a href="' . $loc . '/tags/' . urlencode( $row['name'] ) . '/">' . str_replace('_',' ',$row['name']) . '</a>';
	}
	?>
	<br/>
	Uploaded: <?=date('Y-m-d @ H:i:s', $entry['date']);?> UTC
	<br/>
	Views: <?=$entry['views']; ?>
	<br/>
	Dimentions: <?=$entry['width']?>x<?=$entry['height']?>
	<br/>
	Size: <?=floor($entry['size']/1024)?>kb
	<br/>
	Uploaded by: <?=$entry['ip'];?>
	<br/>
	<a href="<?=$loc;?>/edit/<?=$id;?>/">Edit Info</a>
	<br/>
	<a href="<?=$loc;?>/image/<?=$id;?>/"><img alt="<?=$title;?>" width="<?=$width?>" height="<?=$height?>" src="<?=$loc;?>/image/<?=$id;?>/" /></a>
	</div>
