<?php

function error( $msg ) {
	global $url, $loc;
	$_SESSION['login_errors'][] = $msg;
	sleep(5);
	header('Location: http://' . $url . $loc . '/login/');
	exit();
}

$sql = sprintf("SELECT * FROM users where username='%s'", $db->real_escape_string($_POST['username']));
$result = $db->query( $sql );
$user = $result->fetch_assoc();

if ( sha1("$_POST[password]" . $user['salt']) == $user['password'] ) {
	$auth_token = sha1($user['salt'].time());
	$_SESSION['auth_token'] = $auth_token;
	$_SESSION['auth_id'] = $user['id'];
	$_SESSION['auth_user'] = $user['username'];
	$_SESSION['auth_salt'] = $user['salt'];
	$_SESSION['auth_email'] = $user['email'];
	$_SESSION['auth_email_hash'] = $user['email_hash'];
	setcookie('token',$auth_token,time()+DB_AUTH_TIMEOUT,'/',DB_URL,false,true);
} else {
	error('bad login');
}

header('Location: http://' . $url . $loc . '/');

$db->close();
exit();

?>
