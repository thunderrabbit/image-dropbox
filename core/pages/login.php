<div id="form">

<?php
if ( $_SESSION['login_errors'] ) {
?>
<h3>Errors</h3>
<p class="error"><?php print implode('</p><p class="error">', $_SESSION['login_errors']); ?></p>
<?php
unset( $_SESSION['login_errors'] );
}
?>

<h2>Login</h2>
<p>dont have a login? <a href="http<?=(DB_SECURE)? 's' : '';?>://<?=DB_URL;?><?=DB_LOC;?>/signup/">sign up</a>
<form action="http<?=(DB_SECURE)? 's' : '';?>://<?=DB_URL;?><?=DB_LOC;?>/dologin/" method="post">
<table id="form_table">
	<tr>
		<td>username</td>
		<td><input type="text" name="username" /></td>
	</tr>
	<tr>
		<td>password</td>
		<td><input type="password" name="password" /></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><br/><input type="submit" value="Login" /></td>
	</tr>
</table>
</form>
</div>
