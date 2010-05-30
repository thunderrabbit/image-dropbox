<div id="me">
<h2><?=$user['alias'];?> (<?=$user['username'];?>)</h2>
<table>
	<tr>
		<td colspan="2">
			<img src="http://www.gravatar.com/avatar.php?size=50&gravatar_id=<?=md5($user['email']);?>" alt="<?=$user['username'];?>" width="50" height="50" border="1" /></a>
	<tr>
		<td>Joined:</td>
		<td><?=date('Y-m-d', $user['joindate']);?></td>
	</tr>
</table>
</form>
