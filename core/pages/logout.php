<?php

unset( $_SESSION['auth_id'] );
unset( $_SESSION['auth_user'] );
unset( $_SESSION['auth_token'] );
unset( $_SESSION['auth_salt'] );
unset( $_SESSION['auth_email'] );
unset( $_SESSION['auth_email_hash'] );
setcookie('token','',time()-DB_AUTH_TIMEOUT,'/',DB_URL,false,true);

header('Location: http://' . $url . $loc . '/');

$db->close();
exit();

?>
