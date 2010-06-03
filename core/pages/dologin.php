<?php

if($_POST) {
	try {
		$auth->login($_POST['username'], $_POST['password']);
		redirect();
	} catch(Exception $e) {
		$_SESSION['login_errors'][] = $msg;
		sleep(rand(5,10));
		redirect('login');
	}
} else {
	redirect('login');
}

?>
