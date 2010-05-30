<?php

require DB_PATH . '/core/lib/mysql.php';

$db = new dropbox_mysqli(DB_HOST, DB_USER, DB_PASS, DB_DB);
if(mysqli_connect_errno()) {
	die("Could not connect to database");
}

?>
