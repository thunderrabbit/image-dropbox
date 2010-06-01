<?php

function error( $msg ) {
	$_SESSION['login_errors'][] = $msg;
	sleep(5);
	header('Location: http://' . DB_URL . DB_LOC . '/login/');
}

if($_POST) {
	try {
		$auth->login($_POST['username'], $_POST['password']);
		header('Location: http://' . DB_URL . DB_LOC . '/');
	} catch(Exception $e) {
		error('bad login');
	}
} else {
	header('Location: http://' . DB_URL . DB_LOC . '/login/');
}

$db->close();
exit();

?>
