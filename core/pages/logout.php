<?php

$auth->logout();
header('Location: http://' . DB_URL . DB_LOC . '/');

$db->close();
exit();

?>
