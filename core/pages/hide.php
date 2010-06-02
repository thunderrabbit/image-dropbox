<?php

if ( !isset( $_SESSION['hide'] ) ) 
	$_SESSION['hide'] = true;
else 
	unset( $_SESSION['hide'] );

header('Location: http://' . DB_URL . DB_LOC . '/');


?>
