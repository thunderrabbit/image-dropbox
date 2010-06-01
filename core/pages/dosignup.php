<?php

print "signing user up...";

require DB_PATH . '/core/lib/validate.php';

if ( $authenticated ) die(" WTF? ");

// check if username is "valid"
if ( preg_match( '/^[a-zA-Z0-9]+$/', $_POST['username'] ) ) {
	print "username valid";
	$username = $db->safe( $_POST['username'] );
	// apparently username is ok make sure it doesn't already exist
	$sql = sprintf( "SELECT username FROM users WHERE username='%s'", $username );
	if ( ! $db->exists( $sql ) ) {
		print "username doesn't exist";
		// looks like the username doesn't exist so lets go forth
		// check if the alias contains anything weird
		if ( preg_match( '/^[a-zA-Z0-9\-_.]+$/', $_POST['alias'] ) ) {
			print "alias is valid";
			// apparently alias is ok
			$alias = $db->safe( $_POST['alias'] );
			
			// make sure passwords match
			if ( $_POST['password1'] == $_POST['password2'] ) {
				print "passwords match";
				$password = $db->safe( $_POST['password1'] );
				$salt = sha1( $username );
				$password_hash = sha1( $password . $salt );	
				
				// make sure email is valid
				if ( validate::email( $_POST['email'] ) ) {
					print "email is valid";
					$email = $db->safe( $_POST['email']);
					$email_hash = md5($email);
					// everything seems ok lets make this user!
					$sql = sprintf("INSERT INTO users (username,alias,email,email_hash,password,salt,joindate) VALUES ('%s','%s','%s','%s','%s','%s',UNIX_TIMESTAMP())",
									$username,$alias,$email,$email_hash,$password_hash,$salt);
					if ( ! $db->query( $sql ) ) die('failed query');
					$id = $db->insert_id;

					// user all created! lets setup their auth tokens and such and redirect them to their profile
					$auth_token = sha1($salt.time());
					$_SESSION['auth_token'] = $auth_token;
					$_SESSION['auth_id'] = $id;
					$_SESSION['auth_user'] = $username;
					$_SESSION['auth_salt'] = $salt;
					$_SESSION['auth_email'] = $email;
					$_SESSION['auth_email_hash'] = $email_hash;
					setcookie('token',$auth_token,time()+DB_AUTH_TIMEOUT,'/',DB_URL,false,true);

					header('Location: http://' . DB_URL . '/' . DB_LOC . 'me/' );

				} else {
					print "error";
				}
			} else {
				print "error";
			}
		} else {
			print "error";
		}
	} else {
		print "error";
	}
} else {
	print "error";
}

?>
