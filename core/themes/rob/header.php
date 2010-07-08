<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">	
	<head>
		<link rel="stylesheet" href="<?=DB_LOC;?>/media/theme/rob/css/main.css" type="text/css" media="screen" />
		<title><?=DB_TITLE?></title>
	</head>
	<body>
	<div id="container">
		<div id="header">
			<h1><?=DB_TITLE?></h1>
			<div id="navigation">
				<a href="<?=DB_LOC;?>/">home</a> 
				<a href="<?=DB_LOC;?>/upload/">upload</a>
				<a href="<?=DB_LOC;?>/tagfield/">tags</a>
				<a href="<?=DB_LOC;?>/stats/">statistics</a> 
				<a href="<?=DB_LOC;?>/about/">about</a>
				<a href="<?=DB_LOC;?>/help/">help</a>
				<? if ( $authenticated ): ?>
					<a href="<?=DB_LOC;?>/logout/">logout</a> 
					<a href="<?=DB_LOC;?>/me/"><img src="http://www.gravatar.com/avatar.php?size=50&gravatar_id=<?=$_SESSION['auth_email_hash'];?>" alt="<?=$_SESSION['auth_user'];?>" width="50" height="50" border="1" /></a>
				<? else: ?>
					<a href="<?=DB_LOC;?>/login/">login</a>
				<? endif; ?>
			</div>
		</div>
		<div id="page">
