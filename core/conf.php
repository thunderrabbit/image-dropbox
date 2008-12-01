<?php

$loc = '';
$dataloc = '/var/vhosts/dropbox/core/';

$db = new mysqli("localhost", "photos", "photos", "photos");
if ( mysqli_connect_errno() ) {
	print "Could not connect to database";
	exit();
}

?>
