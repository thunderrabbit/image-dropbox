<?php

$id = intval( $entry );

if ( $_POST ) {


	$sql = sprintf("select password from entries where id=%d", $id );
	$result = $db->query( $sql );
	$entry = $result->fetch_assoc();

	if ( $entry['password'] == $db->real_escape_string( strval( $_POST['password'] ) ) ) {
		$sql = sprintf("delete from entries where id=%d", $id );
		$db->query( $sql );
		header("Location: $loc/");
		exit();
	}

	header("Location: $loc/view/$id/");
	exit();

} else {
	// display form
	?>
	<form action="<?=$loc;?>/delete/<?=$id;?>/" method="post">
		<table id="form_table">
			<tr>
				<td>password</td>
				<td><input type="password" name="password" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" value="Upload" /></td>
			</tr>
		</table>
	</form>
	<?php
}

?>
