<?php

$loc = '';
$path = '/var/vhosts/dropbox-stage';
$version = '0.1.0a';
$url = 'http://dropbox-stage.easytospell.net';

$db = new mysqli("localhost", "photos", "photos", "photos");
if ( mysqli_connect_errno() ) {
	print "Could not connect to database";
	exit();
}

?>
