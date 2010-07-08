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
