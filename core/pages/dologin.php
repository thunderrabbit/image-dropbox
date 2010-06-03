<?php

if($_POST) {
	try {
		$auth->login($_POST['username'], $_POST['password']);
		header('Location: http://' . DB_URL . DB_LOC . '/');
	} catch(Exception $e) {
		$_SESSION['login_errors'][] = $msg;
		sleep(rand(5,10));
		header('Location: http://' . DB_URL . DB_LOC . '/login/');
	}
} else {
	header('Location: http://' . DB_URL . DB_LOC . '/login/');
}

?>
