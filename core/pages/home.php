<?php

$joins = '';
$where = '';

if ( $tags ) {
        $taglist = split( ' ', $tags );
        foreach ( $taglist as $k => $value ) {
                $tag = $db->real_escape_string( strval( $value ) );
                switch ( $value{0} ) {
                        case '-': # don't show entries with these tags
                                $tag = substr( $tag, 1 );
                                $joins .= " left outer join tagmap t{$k} on t{$k}.entry=entries.id && t{$k}.tag=(SELECT id FROM tags WHERE name='$tag') ";
                                $where[] = " t{$k}.tag is null ";
                                break;
                        case '~':
                                # not implemented yet
                                break;
                        default: # show entries with these tags
                                $joins .= " inner join tagmap t{$k} on t{$k}.entry=entries.id && t{$k}.tag=(SELECT id FROM tags WHERE name='$tag') ";
                                break;
                }
        }
        if ( $where ) 
                $where = ' where ' . implode( ' && ', $where );
}

$page = ($entry) ? $entry : 1;

$count = 50;
$offset = $count * ($page - 1);

$sql = sprintf( "select SQL_CALC_FOUND_ROWS id,title from entries %s %s order by date desc limit %d offset %d",
                        $joins, $where, $count, $offset );
$result = $db->query( $sql );
$num = array_pop($db->query("SELECT FOUND_ROWS()")->fetch_row());
if ( ! $result ) die("Failed Query");

?>
	<?php tagField( $db, 50 ); ?>
	<div id="search">
	<form action="/search/" method="get">
	search <input type="text" name="search" value="<?=$tags;?>" />
	</form>
	</div>
	<div id="images">
	<? for($i = 1; $row = $result->fetch_assoc(); $i++ ) { ?>	
		<a title="<?=$row['title'];?>" href="<?=$loc;?>/view/<?=$row['id'];?>/">
			<img src="<?=$loc;?>/thumb/<?=$row['id'];?>/" alt="<?=$row['title'];?>" />
		</a>
	<? } ?>
	</div>
<?
$pages = ceil( $num / $count );
$tagurl = ($tags) ? "/tags/$tags" : '';
if ( $pages > 1 && $page > 1 )
	print "<a href=\"$tagurl/page/" . ($page-1) . "/\">&lt;prev</a>";
if ( $pages > 1 ) {
	for ( $i = 1; $i <= $pages; $i++ ) {
		if ( $i != $page )
			print " <a href=\"$tagurl/page/$i/\">$i</a> ";
		else
			print " $i ";
	}
	if ( $page < $pages )
		print "<a href=\"$tagurl/page/" . ($page+1) . "/\">next&gt;</a>";
}
?>
