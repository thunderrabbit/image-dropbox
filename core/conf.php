<?php

$loc = '';
$path = '/var/vhosts/dropbox.easytospell.net';
$version = '0.2.0a';
$url = 'dropbox.easytospell.net';
$secure = false; // use https for things that require passwords, upload, edit, delete

$db = new mysqli("localhost", "dropbox", "dropbox", "dropbox");
if ( mysqli_connect_errno() ) {
	echo "Could not connect to database";
	exit();
}

?>
