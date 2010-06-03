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
