<?php

$loc = '/dropbox';
$dataloc = '/var/www-data/dropbox/';

$db = new mysqli("localhost", "photos", "photos", "photos");
if ( mysqli_connect_errno() ) {
	print "Could not connect to database";
	exit();
}

?>
