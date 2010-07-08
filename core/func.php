<?php

function fail()
{
	die( 'oops' );
}

function redirect() {
	global $db;
	$path = '/';
	if(func_num_args() > 0) {
		$args = func_get_args();
		$path .= implode('/', $args);
	}
	header('Location: http://' . DB_URL . DB_LOC . $path);
	$db->close();
	exit();
}

function update_hook($entry, $field, $from, $to)
{
	global $db;

	$host = gethostbyaddr($_SERVER['REMOTE_ADDR']) . ' (' . 
			$_SERVER['REMOTE_ADDR'] . ')';
	$sql = sprintf( "insert into " . DB_PREFIX . "updates (`entry`,`ip`,`date`,`change`,`from`,`to`) 
			values (%d,'%s',%d,'%s','%s','%s')", $entry, $host, time(), $field, 
			$from, $to);
	if(!$db->query($sql))
		throw new DBException('update_hook: query error');
}

function debug_hook($msg)
{
	if(DB_DISPLAY_DEBUG)
		trigger_error($msg);
}

function tagField($db,$limit=null)
{
	$sql = "select t.name, m.tag, count(m.tag) as num from " . DB_PREFIX . "tagmap as m, " . DB_PREFIX . "tags as t where t.id=m.tag group by tag order by date desc";
	$sql .= ( !is_null( $limit ) ) ? ' LIMIT ' . $limit : null;
	$max_size = 250;
	$min_size = 100;

	if ( ! $result = $db->query( $sql ) ) die( 'Failed Query' );

	$tags = array();
	while( $row = $result->fetch_assoc() ) {
		$tags[$row['name']] = $row['num'];
	}

	if(count($tags))
	{
		$max_value = max( array_values( $tags ) );
		$min_value = min( array_values( $tags ) );
	}
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

function tagParse(&$db,&$tags,&$sql,&$count)
{
	if ( $tags ) {
		$value = strtok($tags, " \n\t");
		$k == 0;
		while($value) {
			$k++;
	                $tag = $db->real_escape_string( strval( $value ) );
	                switch ( $value{0} ) {
	                        case '-': # don't show entries with these tags
	                                $tag = substr( $tag, 1 );
	                                $joins .= " left outer join " . DB_PREFIX . "tagmap t{$k} on t{$k}.entry=" . DB_PREFIX . "entries.id && t{$k}.tag=(SELECT id FROM " . DB_PREFIX . "tags WHERE name='$tag') ";
	                                $where[] = " t{$k}.tag is null ";
	                                break;
	                        case '~':
	                                # not implemented yet
	                                break;
							case 's': # sorting
								if ( ( strlen( $value ) > 2 ) && $value{1} == ':' ) {
									switch ( substr( $value, 2 ) ) {
										case 's':
										case 'size':
											$sort = 'size';
											break;
										case 'v':
										case 'views':
											$sort = 'views';
											break;
										case 'd':
										case 'date':
										default:
											$sort = 'date';
											break;
									}
									break;
								}
	                        default: # show entries with these tags
								//if ( substr( $value, -1, 1 ) == '*' )
								//	$joins .= " inner join " . DB_PREFIX . "tagmap t{$k} on t{$k}.entry=" . DB_PREFIX . "entries.id && t{$k}.tag=(SELECT id FROM " . DB_PREFIX . "tags WHERE name like '%" .
								//			substr($tag, 0, -1) . "%') ";
								//else
	                                $joins .= " inner join " . DB_PREFIX . "tagmap t{$k} on t{$k}.entry=" . DB_PREFIX . "entries.id && t{$k}.tag=(SELECT id FROM " . DB_PREFIX . "tags WHERE name='$tag') ";
	                            break;
	                }
			$value = strtok(' \n\t');
	        }
	        if ( $where ) 
	                $where = ' && ' . implode( ' && ', $where );
	}
	
	if ( isset( $_SESSION['hide'] ) )
		$where .= ' && safe=1 ';
	
	$page = ($entry) ? $entry : 1;
	
	if ( !$sort )
		$sort = 'date';
	$direction = 'desc';
	$count = 50;
	$offset = $count * ($page - 1);
	
	$sql = sprintf( "select SQL_CALC_FOUND_ROWS id,title,type from " . DB_PREFIX . "entries %s where parent is null %s order by %s %s limit %d offset %d",
	                        $joins, $where, $sort, $direction, $count, $offset );
	print $sql;
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

if (!function_exists('getallheaders'))
{
    function getallheaders()
    {
       foreach ($_SERVER as $name => $value)
       {
           if (substr($name, 0, 5) == 'HTTP_')
           {
               $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
           }
       }
       return $headers;
    }
}

?>
