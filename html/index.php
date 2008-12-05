<?php
require '../core/conf.php';
require $path . "/core/func.php";

if ( $_GET['tags'] ) {
	$sql = sprintf( "select e.id,e.title 
			 from entries as e, tags t, tagmap m 
			 where t.name='%s' && m.tag=t.id && e.id=m.entry 
			 order by e.date desc 
			 limit 50",
				$db->real_escape_string( strval( $_GET['tags'] ) ) );
} else {
	$sql = "SELECT id,title FROM entries order by date desc limit 50";
}
$result = $db->query( $sql );

if ( ! $result ) die("Failed Query");

include $path . "/core/header.php";

?>
	<?php tagField( $db, 50 ); ?>
	<div id="images">
	<? for($i = 1; $row = $result->fetch_assoc(); $i++ ) { ?>	
		<a title="<?=$row['title'];?>" href="<?=$loc;?>/view/<?=$row['id'];?>/">
			<img src="<?=$loc;?>/thumb/<?=$row['id'];?>/" alt="<?=$row['title'];?>" />
		</a>
	<? } ?>
	</div>
<?
$result->close();

include $path . "/core/footer.php";
?>
