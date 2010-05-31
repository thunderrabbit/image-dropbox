<h2>Login</h2>
<? if(isset($_SESSION['login_errors']) && is_array($_SESSION['login_errors'])) {
	foreach($_SESSION['login_errors'] as $err_msg) {
		print "<br>" . $err_msg;
	}
	unset($_SESSION['login_errors']);
}
?>
<p>Don't have a login? <a href="http<?=(DB_SECURE)? 's' : '';?>://<?=DB_URL;?><?=DB_LOC;?>/signup/">sign up</a>
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
