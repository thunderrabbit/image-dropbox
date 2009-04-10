<div id="form">

<h2>Upload</h2>
<form action="<?=$loc;?>/submit/" enctype="multipart/form-data" method="post">
<table id="form_table">
	<tr>
		<td>title</td>
		<td><input type="text" name="title" size="40" /></td>
	</tr>
	<tr>
		<td>tags</td>
		<td><textarea name="tags"></textarea></td>
	</tr>
	<tr>
		<td>file</td>
		<td><input type="file" name="image" /></td>
	</tr>
	<tr>
		<td>worksafe</td>
		<td><input type="radio" name="rating" value="1" />Yes <input type="radio" name="rating" value="0" /> No 
	<tr>
		<td>password (to make changes)</td>
		<td><input type="password" name="password" /></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" value="Upload" /></td>
	</tr>
</table>
</form>
</div>
