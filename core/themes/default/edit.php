	<div id="form">
		<h2>Edit</h3>
		<form action="http<?=(DB_SECURE) ? 's' : '';?>://<?=DB_URL;?><?=DB_LOC;?>/update/<?=$id;?>/" method="post">
		<table id="form_table">
			<tr>
				<td>title</td>
				<td><input type="text" name="title" value="<?=stripslashes( $title ); ?>" /></td>
			</tr>
			<tr>
				<td>tags</td>
				<td><textarea rows="3" cols="30" name="tags"><?=stripslashes( $tags ); ?></textarea></td>
			</tr>
			<tr>
				<td>description</td>
				<td><textarea name="description" rows="8" cols="50"><?=stripslashes( $description ); ?></textarea></td>
			</tr>
			<tr>
				<td>worksafe</td>
				<td>
					<input type="radio" <? if ($rating == 1) echo 'checked="checked"'; ?> name="rating" value="1" /> Yes 
					<input type="radio" <? if ($rating == 0) echo 'checked="checked"'; ?> name="rating" value="0" /> No
				</td>
			</tr>
			<? if($pass): ?>
			<tr>
				<td>password</td>
				<td><input type="password" name="password" value="" /></td>
			</tr>
			<? endif; ?>
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" value="Update" /></td>
			</tr>
		</table>	
		</form>
	</div>
