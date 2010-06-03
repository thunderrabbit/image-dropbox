<?php

function error( $msg ) {
	$_SESSION['signup_errors'][] = $msg;
	redirect('signup');
}

if($_POST) {
	try {
		$auth->signup($_POST['username'], $_POST['password1'],
				$_POST['password2'], $_POST['alias'], $_POST['email']);
		redirect('me');
	} catch(Exception $e) {
		// Should seutp session error array here
		error($e->GetMessage());
		redirect('signup');
	}
} else {
	redirect('signup');
}

?>
