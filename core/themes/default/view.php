	<div id="content">
	<h2>View</h2>
	URL: http://<?=DB_URL;?><?=DB_LOC;?>/view/<?=$id;?>/
	<br/>
	Direct: http://<?=DB_URL;?><?=DB_LOC;?>/image/<?=$id;?>/<?=$filename;?>
	<br/>
	<? if ( $custom ): ?>
	Custom Thumbnail: http://<?=DB_URL;?><?=DB_LOC;?>/thumb/<?=$id;?>/custom/<?=$filename;?>
	<br/>
	<? endif; ?>
	Title: <?=$entry['title'];?>
	<br/>
	Tags: 
	<?
	for($i = 0; $row = $tags->fetch_assoc(); ++$i ) {
		if ( $i > 0 ) echo ', ';
		echo '<a href="' . DB_LOC . '/tags/' . urlencode( $row['name'] ) . '/">' . str_replace('_',' ',$row['name']) . '</a>';
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
	<a href="<?=DB_LOC;?>/track/<?=$id;?>/">Track Changes</a>&nbsp;
	<a href="<?=DB_LOC;?>/edit/<?=$id;?>/">Edit Info</a>&nbsp;
	<a href="<?=DB_LOC;?>/delete/<?=$id;?>/">Delete</a>&nbsp;
	<br/>
	<a href="<?=DB_LOC;?>/image/<?=$id;?>/<?=$filename;?>"><img alt="<?=$title;?>" width="<?=$width?>" height="<?=$height?>" src="<?=DB_LOC;?>/image/<?=$display_id;?>/<?=$filename;?>" /></a>
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
	<form action="<?=DB_LOC;?>/comment/<?=$id;?>/" method="post">
	<input type="hidden" name="verify" value="<?=$_SESSION['verify.' . $id ];?>" />
	name <input type="text" name="name" />
	<br/>
	<textarea cols="40" rows="7" name="content"></textarea>
	<br/>
	<input type="submit" value="Post" />
	</form>
	<? endif; ?>
	</div>
