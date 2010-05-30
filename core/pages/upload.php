<div id="form">

<?php
if ( $_SESSION['upload_errors'] ) {
?>
<h3>Errors</h3>
<p class="error"><?php print implode('</p><p class="error">', $_SESSION['upload_errors']); ?></p>
<?php
unset( $_SESSION['upload_errors'] );
}
include DB_PATH . '/core/themes/' . DB_THEME . '/upload.php';
?>
