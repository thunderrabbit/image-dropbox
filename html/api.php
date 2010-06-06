<pre>
<?php
require '../core/conf.php';
require $path . '/core/func.php';

function tags($db)
{
	$sql = "select name from " . DB_PREFIX . "tags";
	if ( ! $result = $db->query( $sql ) ) die( 'Failed Query' );

	while( $row = $result->fetch_assoc() ) 
		print "$row[name]\n";
}

function images($db)
{
	$sql = "select id from " . DB_PREFIX . "entries";
	if ( ! $result = $db->query( $sql ) ) die( 'Failed Query' );
	while( $row = $result->fetch_assoc() )
		print "http://dropbox.easytospell.net/images/$row[id]/$row[id].jpg\n";
}

function thumbs($db)
{
	$sql = 'select id from entries';
	if ( ! $result = $db->query( $sql ) ) die( 'Failed Query' );
	while( $row = $result->fetch_assoc() )
		print "http://dropbox.easytospell.net/thumb/$row[id]/$row[id].jpg\n";
}


print "GET\n";
print var_dump($_GET);
print "POST\n";
print var_dump($_POST);

$args = explode('/', $_GET['args']);

switch($_GET['cmd']) {
	case 'get':
		switch($args[0]) {
			case 'tags':
				tags($db);
				break;
			case 'images':
				images($db);
				break;
			case 'thumbs':
				thumbs($db);
				break;
		}
		break;
}
?>

api methods:
	get:
		tags:
			namespace:
				password:
		images:
			search:
			namespace:
				password:
		thumbs:
			search:
			namespace:
				password:
		comments:
			image:
			namespace:
				password:
		changes:
			namespace:
				password:
			image:
		stats:
			namespace:
				password:
	put:
		tags:
			namespace:
				password:
			name:
		images:
			namespace:
				password:
			title:
			tags:
			worksafe:
			cthumb:
				size:
				square:
				crop:
			password:
		comments:
			namespace:
				password:
			image:
			name:
			text:
	update:
		images:
			namespace:
				password:
			title:
			tags:
			worksafe:
			password:

			
</pre>
