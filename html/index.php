<?php
require '../core/conf.php';
require $path . "/core/func.php";


if ( $_GET['args'] ) {
	$args = explode('/', $_GET['args'] );

	for ( $i = 0, $c = count($args); $i < $c; $i++ ) {
		switch ( strval( $args[$i] ) ) {
			case 'page':
				$section = 'home';
			case 'view':
			case 'edit':
			case 'update':
			case 'comment':
			case 'track':
			case 'delete':
				if ( ($i+1) < $c )
					$entry = $args[++$i];
				else
					die('problem');
				if ( !$section )
					$section = $args[$i-1];
				break;
			case 'stats':
			case 'about':
			case 'help':
			case 'tagfield':
			case 'upload':
			case 'submit':
			case 'hide':
			case 'search':
				if ( !$section )
					$section = $args[$i];
				break;
			
			case 'tags':
				if ( ($i+1) < $c ) 
					$tags = $args[++$i];
				else
					die('problem');
			default:
				break;
		}
	}
}

if ( !$section ) $section = 'home';

#print "section: $section<br/>";
#print "entry: $entry<br/>";
#print "tags: $tags<br/>";

ob_start();
require $path . "/core/header.php";
require $path . "/core/pages/" . $section . ".php";
require $path . "/core/footer.php";
ob_flush();

?>
