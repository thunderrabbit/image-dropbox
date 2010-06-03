<?php

function fail()
{
	die( 'oops' );
}

function redirect() {
	global $db;
	$path = (func_num_args() > 0) ? implode('/', func_get_args()) : '/';
	header('Location: http://' . DB_URL . DB_LOC . $path);
	$db->close();
	exit();
}

function update_hook($entry, $field, $from, $to)
{
	global $db;

	$host = gethostbyaddr($_SERVER['REMOTE_ADDR']) . ' (' . 
			$_SERVER['REMOTE_ADDR'] . ')';
	$sql = sprintf( "insert into updates (entry,ip,date,field,`from`,`to`) 
			values (%d,'%s',%d,'%s','%s','%s')", $entry, $host, time(), $field, 
			$from, $to);
	if(!$db->query($sql))
		throw Exception('update_hook: query error');
}

function tagField($db,$limit=null)
{
	$sql = 'select t.name, m.tag, count(m.tag) as num from tagmap as m, tags as t where t.id=m.tag group by tag order by date desc';
	$sql .= ( !is_null( $limit ) ) ? ' LIMIT ' . $limit : null;
	$max_size = 250;
	$min_size = 100;

	if ( ! $result = $db->query( $sql ) ) die( 'Failed Query' );

	while( $row = $result->fetch_assoc() ) {
		$tags[$row['name']] = $row['num'];
	}

	$max_value = max( array_values( $tags ) );
	$min_value = min( array_values( $tags ) );
	$spread = $max_value - $min_value;
	if ( $spread == 0 ) $spread = 1;
	$step = ( $max_size - $min_size ) / $spread;

	?>
	<div id="tags">
	<?php 
	foreach ( $tags as $key => $value ) {
		$size = ceil( $min_size + ( ( $value - $min_value ) * $step) );
	?>
		<a style="font-size: <?=$size;?>%" href="<?=DB_LOC;?>/tags/<?=urlencode( $key );?>/">
			<?=str_replace( '_', ' ', $key );?></a>(<?=$value;?>), <? } ?> <a href="<?=DB_LOC;?>/">all</a>
	</div>
	<?php
}

function checkcache( $cache_id ) {
	return ( file_exists( DB_PATH . '/cache/' . $cache_id ) );
}

function imgtypetoext( $type ) {
	switch ( $type ) {
		case 1:
			$ext = 'gif';
			break;
		case 3:
			$ext = 'png';
			break;
		case 2:
		default:
			$ext = 'jpg';
			break;
	}
	return $ext;
}

function imgtypetomime( $type ) {
	return 'image/' . imgtypetoext( $type );
}

?>
