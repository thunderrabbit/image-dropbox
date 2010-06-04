<?php

require DB_PATH . '/core/lib/image.php';

$img = new image($db);

try {
	$img->upload($authenticated);
	redirect('view', $img->getentryid());
} catch (ImageException $e) {
	if($e->rollback)
		$img->rollback();
	$_SESSION['upload_errors'][] = $e->getMessage();
	redirect('upload');
}	

?>
