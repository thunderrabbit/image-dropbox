<?php

$loc = '';
$path = '/data/www';
$version = '0.3.0a';
$url = 'tohru.easytospell.net';
$secure = false; // use https for things that require passwords, upload, edit, delete

// Start migrating to define based config
define('DB_LOC', '');
define('DB_PATH', '/data/www');
define('DB_VERSION', '0.3.0a');
define('DB_URL', 'tohru.easytospell.net');
define('DB_SECURE', false);
define('DB_AUTH_TIMEOUT', 14400); // 4 hours
define('DB_FILESYSTEM', true); // use filesystem istead of database for image contents
define('DB_DATAPATH', '/data/image/'); // where do local image exist

require DB_PATH . '/core/lib/mysql.php';

$db = new dropbox_mysqli("localhost", "dropbox", "dropbox", "dropbox_dev");
if ( mysqli_connect_errno() ) {
	echo "Could not connect to database";
	exit();
}

?>
