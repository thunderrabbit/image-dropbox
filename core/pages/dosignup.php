<?php

if($_POST) {
	try {
		$auth->signup($_POST['username'], $_POST['password1'],
				$_POST['password2'], $_POST['alias'], $_POST['email']);
		header('Location: http://' . DB_URL . '/' . DB_LOC . 'me/' );
	} catch(Exception $e) {
		// Should seutp session error array here
		header('Location: http://' . DB_URL . DB_LOC . '/signup/');
	}
} else {
	header('Location: http://' . DB_URL . DB_LOC . '/signup/');
}

$db->close();
exit();

?>
