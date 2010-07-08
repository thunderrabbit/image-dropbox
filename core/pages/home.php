<?php

// defined in /core/func.php
$count = 50;
tagParse($db,$tags,$sql,$count,$entry);

$result = $db->query( $sql );
$num = array_pop( $db->query( "SELECT FOUND_ROWS()" )->fetch_row() );

if ( ! $result ) die("Failed Query");

?>
	<?php tagField( $db, 50 ); ?>
	<div id="search">
	<form action="/search/" method="get">
	search <input type="text" name="search" value="<?=$tags;?>" />
	</form>
	</div>
	<div id="images">
	<? for($i = 1; $row = $result->fetch_assoc(); ++$i ) { ?>	
		<a title="<?=$row['title'];?>" href="<?=DB_LOC;?>/view/<?=$row['id'];?>/<?=($tags ? 'tags/'.$tags : '')?>">
			<img src="<?=DB_LOC;?>/thumb/<?=$row['id'];?>/<?=$row['id'];?>.<?=imgtypetoext($row['type']);?>" alt="<?=$row['title'];?>" />
		</a>
	<? } ?>
	</div>
<?
$pages = ceil( $num / $count );
$tagurl = ($tags) ? DB_LOC . "/tags/$tags" : DB_LOC;
if ( $pages > 1 && $page > 1 )
	echo "<a href=\"$tagurl/page/" . ($page-1) . "/\">&lt;prev</a>";
if ( $pages > 1 ) {
	for ( $i = 1; $i <= $pages; ++$i ) {
		if ( $i != $page )
			echo " <a href=\"$tagurl/page/$i/\">$i</a> ";
		else
			echo " $i ";
	}
	if ( $page < $pages )
		echo "<a href=\"$tagurl/page/" . ($page+1) . "/\">next&gt;</a>";
}
?>
