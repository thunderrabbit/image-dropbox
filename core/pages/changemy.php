<?

if ( ! $authenticated ) header('Location: /' . DB_LOC);

function error( $msg ) {
	global $url, $loc;
	$_SESSION['changemy_errors'][] = $msg;
	sleep(5);
	header('Location: http://' . $url . $loc . '/me/');
	exit();
}

if ( $_POST['new_password1'] == $_POST['new_password2'] ) {
	$sql = sprintf("SELECT password FROM users where id=%d", $_SESSION['auth_id']);
	$result = $db->query( $sql );
	$user = $result->fetch_assoc();

	if ( sha1("$_POST[current_password]" . $_SESSION['auth_salt']) == $user['password'] ) {
		$new_pass = sha1( strval( $_POST['new_password1'] ) . $_SESSION['auth_salt'] );
		$sql = sprintf( "UPDATE users SET password='%s' WHERE id=%d", $new_pass, $_SESSION['auth_id'] );
		$db->query( $sql );
	} else {
		sleep(5);
		error('current password incorrect');	
	}
} else {
	error('new passwords do not match');
}

header('Location: http://' . $url . $loc . '/me/');
exit();
