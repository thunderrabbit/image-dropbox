<?php

class DBException extends Exception {
	public function __construct($message = null, $code=0) {
		debug_hook($message);
		parent::__construct($message, $code);
	}
}

class ImageException extends DBException {
	public $rollback;

	public function __construct($message = null, $rollback=false, $code = 0) {
		$this->rollback = $rollback;
		parent::__construct($message, $code);
	}
}

?>
