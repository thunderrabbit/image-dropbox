<?php

$sql = sprintf("SELECT * FROM users WHERE username='%s'", $db->safe($entry));
if ( ! $result = $db->query( $sql ) ) die('Failed Query');

$user = $result->fetch_assoc();
$result->free();

include DB_PATH . '/core/themes/' . DB_THEME . '/user.php';
?>
