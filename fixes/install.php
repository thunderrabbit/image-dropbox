<?
require '../core/conf.php';
require DB_PATH . "/core/db.php";
require DB_PATH . "/core/session.php";
require DB_PATH . "/core/func.php";

// see if tables exist
$sql = "SELECT `table_name` FROM `information_schema`.`tables`
		 WHERE `table_name` IN ('comments', 'updates', 'tagmap', 'tags', 'thumbs', 'data', 'entries', 'namespace')";
$result = $db->query($sql);
print_r ($result);
exit;
// require password to be sent

// if password was sent, then drop all tables

//drop all tables
#$sql = "DROP TABLE IF EXISTS `comments`, `updates`, `tagmap`, `tags`, `thumbs`, `data`, `entries`, `namespace`;";
#$result = $db->query( $sql );

?>

// create new tables

