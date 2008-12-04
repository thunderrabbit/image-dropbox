<?php

$loc = '';
$dataloc = '/var/vhosts/dropbox/core/';
$version = '0.0.1a';

$db = new mysqli("localhost", "photos", "photos", "photos");
if ( mysqli_connect_errno() ) {
	print "Could not connect to database";
	exit();
}

?>
