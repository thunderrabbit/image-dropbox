<?
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
		<tr>
			<td><a href="http://<?=DB_URL . DB_LOC; ?>">No</a></td>
		</tr>
	</table>
	</form>
<?
} else {
?>
	<form action="http<?=(DB_SECURE) ? 's' : '';?>://<?=DB_URL . DB_LOC;?>/delete/<?=$id;?>/" method="post">
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
<?
}
?>
