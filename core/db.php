<?php

$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_DB);
if(mysqli_connect_errno()) {
	die("Could not connect to database");
}

?>
