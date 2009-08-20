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
	$conrim = false;
	if ( $authenticated ) {
		$sql = sprintf("select id from entries where id=%d and user=%d", $id, $_SESSION['auth_id'] );
		if ( $db->exists( $sql ) ) {
			$confirm = true;
		}
	}
	if ( $confirm ) {
	?>
	<form action="http<?=(DB_SECURE) ? 's' : '';?>://<?=DB_URL . DB_LOC; ?>/delete/<?=$id;?>/" method="post">
	<table id="form_table">
		<tr>
			<td>Are you sure?</td>
		</tr>
		<tr>
			<td><input type="submit" name="submit" value="Yes" /></td>
		</tr>
	</table>
	</form>
	<?
	} else {
	?>
	<form action="http<?=($secure) ? 's' : '';?>://<?=DB_URL . DB_LOC;?>/delete/<?=$id;?>/" method="post">
		<table id="form_table">
			<tr>
				<td>password</td>
				<td><input type="password" name="password" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" value="Delete" /></td>
			</tr>
		</table>
	</form>
	<?php
	}
}

?>
