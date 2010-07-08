	<div id="content">

	<div class="title"><?=$entry['title'];?></div>
	<? if ( $custom ): ?>
	Custom Thumbnail: http://<?=DB_URL;?><?=DB_LOC;?>/thumb/<?=$id;?>/custom/<?=$filename;?>
	<br/>
	<? endif; ?>
	<div class="tags">
	Tags: 
	<?
	for($i = 0; $row = $tags->fetch_assoc(); ++$i ) {
		if ( $i > 0 ) echo ', ';
		echo '<a href="' . DB_LOC . '/tags/' . urlencode( $row['name'] ) . '/">' . str_replace('_',' ',$row['name']) . '</a>';
	}
	?>
	</div>
	<div class="description"><?=$entry['description']; ?></div>
	<a href="<?=DB_LOC;?>/track/<?=$id;?>/">Track Changes</a>&nbsp;
	<a href="<?=DB_LOC;?>/edit/<?=$id;?>/">Edit Info</a>&nbsp;
	<a href="<?=DB_LOC;?>/delete/<?=$id;?>/">Delete</a>&nbsp;
	<br/>
	<a href="<?=DB_LOC;?>/image/<?=$id;?>/<?=$filename;?>"><img alt="<?=$entry['title'];?>" width="<?=$width?>" height="<?=$height?>" src="<?=DB_LOC;?>/image/<?=$display_id;?>/<?=$filename;?>" /></a>
	<br/>
	<h3>Comments:</h3>
	<?php
	$sql = sprintf( "select * from " . DB_PREFIX . "comments where entry=%d order by date desc", $id );
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
