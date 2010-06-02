<?php

if ( $_GET['search'] ) {
	DB_LOC = sprintf('/tags/%s/', strval($_GET['search']));
} else {
	DB_LOC = '/';
}

redirect();

?>
