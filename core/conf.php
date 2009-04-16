<?php

$loc = '';
$path = '/var/vhosts/dropbox-stage';
$version = '0.1.0a';
$url = 'dropbox-stage.easytospell.net';
$secure = true; // use https for things that require passwords, upload, edit, delete

$db = new mysqli("localhost", "dropbox", "dropbox", "dropbox");
if ( mysqli_connect_errno() ) {
	echo "Could not connect to database";
	exit();
}

?>
