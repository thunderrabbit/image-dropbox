	</div>
	<div id="footer">
	<a href="<?=DB_LOC;?>/">home</a> 
	<a href="<?=DB_LOC;?>/upload/">upload</a>
	<a href="<?=DB_LOC;?>/tagfield/">tags</a>
	<a href="<?=DB_LOC;?>/stats/">statistics</a> 
	<a href="<?=DB_LOC;?>/about/">about</a>
	<a href="<?=DB_LOC;?>/help/">help</a> 
	<a href="<?=DB_LOC;?>/track/">changes</a>
	[<a href="<?=DB_LOC;?>/hide/"><?=($_SESSION['hide']) ? '' : 'not ';?>worksafe</a>]
	<br/>
	<? if(file_exists(DB_PATH . '/core/local_footer.php')) {
		include DB_PATH . '/core/local_footer.php';
	} ?>
	<span id="copy">&copy;<?=date('Y');?> <a href="http://www.easytospell.net">Richard Marshall</a> v<?=DB_VERSION;?></span>
	</div>
	</div>
</body>
</html>
