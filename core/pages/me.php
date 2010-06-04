<?php

if (!$authenticated)
	redirect();

$sql = sprintf("SELECT * FROM users WHERE id=%d", $_SESSION['auth_id']);
if ( ! $result = $db->query( $sql ) ) die('Failed Query');

$user = $result->fetch_assoc();
$result->free();

include DB_PATH . '/core/themes/' . DB_THEME . '/me.php';
?>
