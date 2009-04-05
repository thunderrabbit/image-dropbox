<?php
require '../core/conf.php';
require $path . "/core/func.php";

$joins = '';
$where = '';

if ( $_GET['tags'] ) {
        $tags = split( ' ', $_GET['tags'] );
        foreach ( $tags as $k => $value ) {
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

$sql = sprintf( "select id,title from entries %s %s order by date desc limit 100",
                        $joins, $where );
$result = $db->query( $sql );

if ( ! $result ) die("Failed Query");

include $path . "/core/header.php";

?>
	<?php tagField( $db, 50 ); ?>
	<div id="search">
	<form action="/" method="get">
	search <input type="text" name="tags" value="<?=$_GET['tags'];?>" />
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
$result->close();

include $path . "/core/footer.php";
?>
