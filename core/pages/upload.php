<div id="form">

<?php
if ( $_SESSION['upload_errors'] ) {
?>
<h3>Errors</h3>
<p class="error"><?php print implode('</p><p class="error">', $_SESSION['upload_errors']); ?></p>
<?php
unset( $_SESSION['upload_errors'] );
}
?>

<h2>Upload</h2>
<form action="http<?=($secure)? 's' : '';?>://<?=DB_URL;?><?=DB_LOC;?>/submit/" enctype="multipart/form-data" method="post">
<table id="form_table">
	<tr>
		<td>title</td>
		<td><input type="text" name="title" size="40" /></td>
	</tr>
	<tr>
		<td>tags</td>
		<td><textarea rows="3" cols="30" name="tags"></textarea></td>
	</tr>
	<tr>
		<td>file</td>
		<td><input type="file" name="image" /></td>
	</tr>
	<tr>
		<td>worksafe</td>
		<td><input type="radio" checked="checked" name="rating" value="1" />Yes <input type="radio" name="rating" value="0" /> No 
	</tr>
	<tr><td colspan="2"><br/></td></tr>
	<tr>
		<td>create custom thumb</td>
		<td><input type="radio" name="custom" value="1" />Yes <input type="radio" checked="checked" name="custom" value="0" /> No 
	</tr>
	<tr>
		<td>custom thumb size</td>
		<td>
			<select name="size">
				<option>100</option>
				<option>150</option>
				<option>200</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>create square thumb</td>
		<td><input type="radio" name="square" value="1" />Yes <input type="radio" checked="checked" name="square" value="0" /> No
	</tr>
	<tr>
		<td>square crop mode</td>
		<td>
			<select name="crop">
				<option value="1">top / left</option>
				<option value="2">middle</option>
				<option value="3">bottom / right</option>
			</select>
		</td>
	</tr>
	<? if ( !$authenticated ): ?>
	<tr><td colspan="2"><br/></td></tr>
	<tr>
		<td>password (to make changes)</td>
		<td><input type="password" name="password" /></td>
	</tr>
	<? endif; ?>
	<tr>
		<td>&nbsp;</td>
		<td><br/><input type="submit" value="Upload" /></td>
	</tr>
</table>
</form>
</div>
