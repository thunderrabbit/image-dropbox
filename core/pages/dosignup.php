<?php

function error( $msg ) {
	$_SESSION['login_errors'][] = $msg;
	sleep(5);
	header('Location: http://' . DB_URL . DB_LOC . '/login/');
}

if($_POST) {
	try {
		$auth->signup($_POST['username'], $_POST['password1'],
				$_POST['password2'], $_POST['alias'], $_POST['email']);
		header('Location: http://' . DB_URL . '/' . DB_LOC . 'me/' );
	} catch(Exception $e) {
		// Should seutp session error array here
		error($e->GetMessage());
		header('Location: http://' . DB_URL . DB_LOC . '/signup/');
	}
} else {
	header('Location: http://' . DB_URL . DB_LOC . '/signup/');
}

$db->close();
exit();

?>
