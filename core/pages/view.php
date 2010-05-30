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

$sql = sprintf( "select t.name from tags t, tagmap m where m.entry=%d && t.id=m.tag", $id );

$info = $result->fetch_assoc();

$filename = $id . '.' . imgtypetoext( $entry['type'] );

if ( $entry['user'] > 0 ) {
	$sql = sprintf('select alias,username from users where id=%d', $entry['user']);
	if ( $info = $db->select( $sql ) ) {
		$user= '<a href="http://' . DB_URL . DB_LOC . '/user/profile/' . $info['username'] . '/">' . $info['alias'] . '</a>';
	}
} else {
	$user = $entry['ip'];
}

if ( ! $result = $db->query( $sql ) ) {
	die( "Query Error" );
}

?>
	<div id="content">
	<h2>View</h2>
	URL: http://<?=$url;?><?=$loc;?>/view/<?=$id;?>/
	<br/>
	Direct: http://<?=$url;?><?=$loc;?>/image/<?=$id;?>/<?=$filename;?>
	<br/>
	<? if ( $custom ): ?>
	Custom Thumbnail: http://<?=$url;?><?=$loc;?>/thumb/<?=$id;?>/custom/<?=$filename;?>
	<br/>
	<? endif; ?>
	Title: <?=$entry['title'];?>
	<br/>
	Tags: 
	<?
	for($i = 0; $row = $result->fetch_assoc(); ++$i ) {
		if ( $i > 0 ) echo ', ';
		echo '<a href="' . $loc . '/tags/' . urlencode( $row['name'] ) . '/">' . str_replace('_',' ',$row['name']) . '</a>';
	}
	?>
	<br/>
	Worksafe: <?=($entry['safe'] == 1) ? 'Yes' : 'No'; ?>
	<br/>
	Uploaded: <?=date('Y-m-d @ H:i:s', $entry['date']);?> UTC
	<br/>
	Views: <?=$entry['views']; ?>
	<br/>
	Dimentions: <?=$entry['width']?>x<?=$entry['height']?>
	<br/>
	Size: <?=floor($entry['size']/1024)?>kb
	<br/>
	SHA-1 Hash: <?=$entry['hash'];?>
	<br/>
	Uploaded by: <?=$user;?>
	<br/>
	<?
	$exif = exif_read_data('http://dropbox-dev.easytospell.net/image/' . $id . '.jpg', 0, true);
	echo "EXIF:<br />\n";
	foreach ($exif as $key => $section) {
		foreach ($section as $name => $val) {
			$kn = $key.$name;
			switch ( $kn ) {
				case 'IFD0Model':
				case 'EXIFExposureTime':
				case 'EXIFFNumber':
				case 'EXIFISOSpeedRatings':
					echo "$name: $val<br />\n";
					break;
			}
		}
	}
	?>
	<br/>
	<a href="<?=$loc;?>/track/<?=$id;?>/">Track Changes</a>&nbsp;
	<a href="<?=$loc;?>/edit/<?=$id;?>/">Edit Info</a>&nbsp;
	<a href="<?=$loc;?>/delete/<?=$id;?>/">Delete</a>&nbsp;
	<br/>
	<a href="<?=$loc;?>/image/<?=$id;?>/<?=$filename;?>"><img alt="<?=$title;?>" width="<?=$width?>" height="<?=$height?>" src="<?=$loc;?>/image/<?=$display_id;?>/<?=$filename;?>" /></a>
	<br/>
	<h3>Comments:</h3>
	<?php
	$sql = sprintf( "select * from comments where entry=%d order by date desc", $id );
	$result = $db->query( $sql );
	while( $row = $result->fetch_assoc() ) {
	?>
	<p>
	<strong><?=$row['name'];?> (<?=$row['ip'];?>)</strong> - <?=date('Y-m-d @ H:i:s', $row['date']);?><br/>
	<?=htmlentities( $row['content'] );?>
	</p>
	<?
	}
	?>
	<? if(DB_COMMENTS): ?>
	<form action="<?=$loc;?>/comment/<?=$id;?>/" method="post">
	<input type="hidden" name="verify" value="<?=$_SESSION['verify.' . $id ];?>" />
	name <input type="text" name="name" />
	<br/>
	<textarea cols="40" rows="7" name="content"></textarea>
	<br/>
	<input type="submit" value="Post" />
	</form>
	<? endif; ?>
	</div>
