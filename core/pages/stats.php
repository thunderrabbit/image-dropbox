<?php

$stats_file = DB_PATH . "/core/stats.php";
$stats_date = file_exists($stats_file) ? filemtime( $stats_file ) : 0;

// GENERATE STATISTICS (once an hour)
if ( ( time() - $stats_date >= 3600 ) ) {

$sql = "SELECT count(*) count FROM entries";
$result = $db->query( $sql );
$row = $result->fetch_array();
$num_images = $row[0];
$result->close();

$sql = "SELECT count(*) count FROM data";
$result = $db->query( $sql );
$row = $result->fetch_array();
$num_chunks = $row[0];
$result->close();

$sql = "SELECT SUM(size) size FROM entries";
$result = $db->query( $sql );
$row = $result->fetch_array();
$db_size = round( $row[0] / 1024 / 1024, 2 ); 
$result->close();

$sql = "SELECT DISTINCT ip FROM entries";
$result = $db->query( $sql );
$ips = $result->num_rows;
$result->close();

$sql = "SELECT count(*) count FROM tags";
$result = $db->query( $sql );
$row = $result->fetch_array();
$num_tags = $row[0];
$result->close();

$sql = "SELECT tag, count(entry) num from tagmap group by tag";
$result = $db->query( $sql );
while ( $row = $result->fetch_assoc() ) {
	if ( isset( $min ) ) {
		if ( $row['num'] < $min['num'] ) {
			$min = $row;
		}
	} else {
		$min = $row;
	}
	if ( isset( $max ) ) {
		if ( $row['num'] > $max['num'] ) {
			$max = $row;
		}
	} else {
 		$max = $row;
	}
}
$result->close();

$sql = "SELECT name from tags where id=" . $min['tag'];
$result = $db->query( $sql );
$row = $result->fetch_array();
$tag_min = $row[0];
$result->close();

$sql = "SELECT name from tags where id=" . $max['tag'];
$result = $db->query( $sql );
$row = $result->fetch_array();
$tag_max = $row[0];
$result->close();

$sql = "SELECT SUM(views) sum FROM entries";
$result = $db->query( $sql );
$row = $result->fetch_array();
$num_views = $row[0];
$result->close();

$sql = "SELECT id,title,views FROM entries ORDER BY views DESC LIMIT 1";
$result = $db->query( $sql );
$row = $result->fetch_array();
$most_views = $row;
$result->close();

// Date of oldest image
$sql = "SELECT date,id FROM entries order by date limit 1";
$result = $db->query( $sql );
$row = $result->fetch_array();
$oldest_image = $row;
$result->close();

// Date of newest image
$sql = "SELECT date,id FROM entries order by date desc limit 1";
$result = $db->query( $sql );
$row = $result->fetch_array();
$newest_image = $row;
$result->close();

$stats_data = '<?php ';
$stats_data .= sprintf( '%s = "%s";', '$num_images', $num_images );
$stats_data .= sprintf( '%s = "%s";', '$num_chunks', $num_chunks );
$stats_data .= sprintf( '%s = "%s";', '$db_size', $db_size );
$stats_data .= sprintf( '%s = "%s";', '$num_views', $num_views );
$stats_data .= sprintf( '%s = "%s";', '$ips', $ips );
$stats_data .= sprintf( '%s = "%s";', '$num_tags', $num_tags );
$stats_data .= sprintf( '%s = "%s";', '$tag_min', $tag_min );
$stats_data .= sprintf( '%s = "%s";', '$tag_max', $tag_max );
$stats_data .= sprintf( '%s = %s;', '$oldest_image', var_export( $oldest_image, true ) );
$stats_data .= sprintf( '%s = %s;', '$newest_image', var_export( $newest_image, true ) );
$stats_data .= sprintf( '%s = %s;', '$most_views', var_export( $most_views, true ) );
$stats_data .= '?>';

file_put_contents( $stats_file, $stats_data );

} else {
	include $stats_file;
}

include DB_PATH . '/core/themes/' . DB_THEME . '/stats.php';
?>
