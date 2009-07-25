<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">	
	<head>
		<link rel="stylesheet" href="<?=$loc;?>/css/main.css" type="text/css" media="screen" />
		<title>Image DropBox</title>
	</head>
	<body>
	<div id="container">
		<div id="header">
			<h1>Image DropBox<span class="beta">beta</span></h1>
			<div id="navigation">
				<a href="<?=$loc;?>/">home</a> 
				<a href="<?=$loc;?>/upload/">upload</a>
				<a href="<?=$loc;?>/tagfield/">tags</a>
				<a href="<?=$loc;?>/stats/">statistics</a> 
				<a href="<?=$loc;?>/about/">about</a>
				<a href="<?=$loc;?>/help/">help</a>
				<? if ( $authenticated ): ?>
					<a href="<?=DB_LOC;?>/logout/">logout</a> 
					<img src="http://www.gravatar.com/avatar.php?size=50&gravatar_id=<?=$_SESSION['auth_email_hash'];?>" alt="<?=$_SESSION['auth_user'];?>" width="50" height="50" border="1" />
				<? else: ?>
					<a href="<?=DB_LOC;?>/login/">login</a>
				<? endif; ?>
			</div>
		</div>
		<div id="page">
