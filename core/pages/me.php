<?php

if ( ! $authenticated ) header('Location: /' . DB_LOC);

$sql = sprintf("SELECT * FROM users WHERE id=%d", $_SESSION['auth_id']);
if ( ! $result = $db->query( $sql ) ) die('Failed Query');

$user = $result->fetch_assoc();
$result->free();

?>
<div id="me">
<h2><?=$user['alias'];?> (<?=$user['username'];?>)</h2>
<table>
	<tr>
		<td>Joined:</td>
		<td><?=date('Y-m-d', $user['joindate']);?></td>
	</tr>
	<tr>
		<td>Email:</td>
		<td><?=$user['email'];?></td>
	</tr>
</table>
<h3>Update Password:</h3>
<form action="https://<?=DB_URL;?>/<?=DB_LOC;?>changemy/password/" method="post">
<table>
	<tr>
		<td>Current Password:</td>
		<td><input type="password" name="current_password" /></td>
	</tr>
	<tr>
		<td>New Password:</td>
		<td><input type="password" name="new_password1" /></td>
	</tr>
	<tr>
		<td>Confirm New Password:</td>
		<td><input type="password" name="new_password2" /></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" value="Update" /></td>
	</tr>
</table>
</form>
