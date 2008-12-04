<?php

$loc = '';
$path = '/var/vhosts/dropbox';
$version = '0.0.2a';


$db = new mysqli("localhost", "photos", "photos", "photos");
if ( mysqli_connect_errno() ) {
	print "Could not connect to database";
	exit();
}

?>
