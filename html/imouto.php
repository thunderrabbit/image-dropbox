<pre><?php
// Build tags and tagmap entries for moe.imouto.org images

require '../core/conf.php';

$sql = 'SELECT id,title from entries where title like \'moe %\'';
$result = $db->query($sql);
while( $row = $result->fetch_assoc() ) {
	$title = $row['title'];
	$entry = $row['id'];	

	$parts = explode(' ', $title);
	array_shift($parts);
	array_shift($parts);
	$ext = explode('.', $parts[count($parts)-1]);
	$parts[count($parts)-1] = $ext[0];
	
	foreach( $parts as $new ) {
		$rel = $db->query("select id from tags where name='" . $new . "'");
		if ( $rel->num_rows == 0 ) {
			$sql = sprintf("insert into tags (name,date) values ('%s',%d)",$db->real_escape_string($new),time());
		//	print $sql . "\n";
			$db->query( $sql );
			$tag = $db->insert_id;
		} else {
			$row = $rel->fetch_assoc();
			$tag = $row['id'];
		}
		$sql = sprintf("SELECT tag FROM tagmap WHERE tag=%d and entry=%d",$tag,$entry);
		//print $sql . "\n";
		$rel = $db->query( $sql );
		if ( $rel->num_rows == 0 ) {
			$sql = sprintf("insert into tagmap (tag,entry) values (%d,%d)", $tag,$entry);
		//	print $sql . "\n";
			$db->query($sql);
		}
	}
	print "Done with $title...\n";
}

print "Done...";

?></pre>
