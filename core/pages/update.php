<?php

require_once DB_PATH . '/core/lib/entry.php';

if ( $_POST ) {
	$id = intval($entry);
	try {
		$entry = new Entry($db, $id);
		if(!($authenticated && ($entry->get('user') == $_SESSION['auth_id'] || $_SESSION['auth_admin'])))
			$entry->check_pass($_POST['password']);
		$entry->update_tags($_POST['tags']);
		$entry->update('title', $_POST['title']);
		$entry->update('safe', intval($_POST['rating']));
		$entry->save();
		redirect('view', $id);
	} catch(Exception $e) {
		die($e->GetMessage());
		redirect('edit', $id);
	}
} else {
	redirect();
}

?>
