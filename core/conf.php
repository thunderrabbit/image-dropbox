<?php

$loc = '';
$path = '/var/vhosts/dropbox';
$version = '0.0.4a';
$url = 'http://dropbox.easytospell.net';

$db = new mysqli("localhost", "photos", "photos", "photos");
if ( mysqli_connect_errno() ) {
	print "Could not connect to database";
	exit();
}

?>
