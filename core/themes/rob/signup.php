<div id="form">

<?php
if ( $_SESSION['signup_errors'] ) {
?>
<h3>Errors</h3>
<p class="error"><?php print implode('</p><p class="error">', $_SESSION['signup_errors']); ?></p>
<?php
unset( $_SESSION['signup_errors'] );
}
?>

<h2>Signup</h2>
<form action="http<?=(DB_SECURE)? 's' : '';?>://<?=DB_URL;?><?=DB_LOC;?>/dosignup/" method="post">
<table id="form_table">
	<tr>
		<td>username</td>
		<td><input type="text" name="username" /></td>
	</tr>
	<tr>
		<td>name</td>
		<td><input type="text" name="alias" /></td>
	</tr>
	<tr>
		<td>email</td>
		<td><input type="text" name="email" /></td>
	</tr>
	<tr>
		<td>password</td>
		<td><input type="password" name="password1" /></td>
	</tr>
	<tr>
		<td>confirm password</td>
		<td><input type="password" name="password2" /></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><br/><input type="submit" value="Signup" /></td>
	</tr>
</table>
</form>
</div>
