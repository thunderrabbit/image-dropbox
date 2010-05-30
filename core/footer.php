	</div>
	<div id="footer">
	<a href="<?=DB_LOC;?>/">home</a> 
	<a href="<?=DB_LOC;?>/upload/">upload</a>
	<a href="<?=DB_LOC;?>/tagfield/">tags</a>
	<a href="<?=DB_LOC;?>/stats/">statistics</a> 
	<a href="<?=DB_LOC;?>/about/">about</a>
	<a href="<?=DB_LOC;?>/help/">help</a> 
	<a href="<?=DB_LOC;?>/track/">changes</a>
	<a href="http://track.easytospell.net/">bugs</a>
	[<a href="<?=DB_LOC;?>/hide/"><?=($_SESSION['hide']) ? '' : 'not ';?>worksafe</a>]
	<br/>
	<span id="copy">&copy;<?=date('Y');?> <a href="http://www.easytospell.net">Richard Marshall</a> v<?=$version;?></span>
	</div>
	</div>
	<script type="text/javascript">
		var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
		document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
		</script>
		<script type="text/javascript">
		try {
		var pageTracker = _gat._getTracker("UA-6458318-1");
		pageTracker._trackPageview();
		} catch(err) {}</script>
</body>
</html>
<?php
$db->close();
?>
