<?php
$id = intval( $entry );
?>

<h3>Updates:</h3>
<?php
$sql = sprintf( "select * from updates where entry=%d order by date desc", $id );
$result = $db->query( $sql );
while ( $row = $result->fetch_assoc() ) {
?>
<strong><?=$row['ip'];?></strong> updated (<?=$row['field'];?>) from '<?=$row['from'];?>' to '<?=$row['to'];?>' on <?=date('Y-m-d @ H:i:s', $row['date']);?><br/>
<?
}
?>
