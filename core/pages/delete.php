<?php

$id = intval( $entry );

if ( $_POST ) {

	$sql = sprintf("select password,user from entries where id=%d", $id );
	$result = $db->query( $sql );
	$entry = $result->fetch_assoc();

	if ( ( $authenticated && ( $_SESSION['auth_id'] == $entry['user'] ) ) || 
			( $entry['password'] == sha1( strval( $_POST['password'] ) ) ) ) {
		$sql = sprintf("delete from entries where id=%d", $id );
		$db->query( $sql );
		header('Location: http://' . DB_URL . DB_LOC . '/');
		exit();
	}

	header('Location: http://' . DB_URL . DB_LOC .  "/view/$id/");
	exit();

} else {
	// display form
	$confrim = false;
	if ( $authenticated ) {
		$sql = sprintf("select id from entries where id=%d and user=%d", $id, $_SESSION['auth_id'] );
		if ( $db->exists( $sql ) ) {
			$confirm = true;
		}
	}
	if ( $confirm ) {
		include DB_PATH . '/core/themes/' . DB_THEME . '/confirmdelete.php';
	} else {
		include DB_PATH . '/core/themes/' . DB_THEME . '/delete.php';
	}
}

?>
