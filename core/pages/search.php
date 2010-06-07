<?php

if ( $_GET['search'] ) {
	redirect("tags",strval($_GET['search']));
} else {
	redirect();
}

?>
