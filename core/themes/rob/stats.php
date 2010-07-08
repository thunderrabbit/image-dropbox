<div id="statistics">
<h2>Statistics</h2>
<? if($stats_date) : ?>
<p>Stats are generated once an hour</p>
<p>stats last generated: <?=date( 'Y-m-d @ H:i:s', $stats_date );?> UTC</p>
<? else : ?>
<p>Generating stats for the first time...
<? endif; ?>
<table>
	<tr>
		<td>Number of uploaded images:</td>
		<td><?=$num_images;?></td>
	</tr>
	<tr>
		<td>Total size of database:</td>
		<td><?=$db_size;?>mb</tb>
	</tr>
	<tr>
		<td>Number of image chunks:</td>
		<td><?=$num_chunks;?></td>
	</tr>
	<tr>
		<td>Number of image views:</td>
		<td><?=$num_views;?></td>
	</tr>
	<tr>
		<td>Image with most views:</td>
		<td><a href="<?=DB_LOC;?>/view/<?=$most_views[0];?>/"><?=$most_views[2];?> - <?=$most_views[1];?></a></td>
	</tr>
	<tr>
		<td>Oldest image uploaded on:</td>
		<td><a href="<?=DB_LOC;?>/view/<?=$oldest_image[1];?>/"><?=date('Y-m-d @ H:i:s', $oldest_image[0] ); ?> UTC</a></td>
	</tr>
	<tr>
		<td>Newest image uploaded on:</td>
		<td><a href="<?=DB_LOC;?>/view/<?=$newest_image[1];?>/"><?=date('Y-m-d @ H:i:s', $newest_image[0] ); ?> UTC</a></td>
	</tr>
	<tr>
		<td>Average uploads per day:</td>
		<td><?=round( $num_images / ( ceil( ( $newest_image[0] - $oldest_image[0] ) / 82400 ) + 1 ), 2 );?></td>
	</tr>
	<tr>
		<td>Distinct uploaders:</td>
		<td><?=$ips;?></td>
	</tr>
	<tr>
		<td>Number of tags:</td>
		<td><?=$num_tags;?></td>
	</tr>
	<tr>
		<td>Tag with most images:</td>
		<td><a href="<?=DB_LOC;?>/tags/<?=urlencode($tag_max);?>/"><?=$tag_max;?></a></td>
	</tr>
	<tr>
		<td>Tag with least images:</td>
		<td><a href="<?=DB_LOC;?>/tags/<?=urlencode($tag_min);?>/"><?=$tag_min;?></a></td>
	</tr>
</table>

</div>

