<?php

$loc = '';
$path = '/var/vhosts/dropbox-dev.easytospell.net';
$version = '0.3.0a';
$url = 'dropbox-dev.easytospell.net';
$secure = false; // use https for things that require passwords, upload, edit, delete

// Start migrating to define based config
define('DB_LOC', '');
define('DB_PATH', '/var/vhosts/dropbox-dev.easytospell.net');
define('DB_VERSION', '0.3.0a');
define('DB_URL', 'dropbox-dev.easytospell.net');
define('DB_SECURE', false);

$db = new mysqli("localhost", "dropbox", "dropbox", "dropbox");
if ( mysqli_connect_errno() ) {
	echo "Could not connect to database";
	exit();
}

?>
