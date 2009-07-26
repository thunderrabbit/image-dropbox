<?php

// Image management class, deals with image manipulations for the creation of
// thumbnails and previews as well as inserting new entries into the database.

class tag {
	private $db;
	private $errors;

	public function __construct($db) { 
		$this->db = $db;	
		$this->errors = array();
	}

	public function __destruct() { 
	}
	
	public function getall() {
		return $this->get();
	}

	public function get($limit = null) {
		$sql = "select t.name, m.tag, count(m.tag) as num from tagmap as m, tags as t where t.id=m.tag group by tag order by date desc";
		$sql .= ( !is_null( $limit ) ) ? ' LIMIT ' . $limit : null;		
		if ( ! $result = $this->db->query( $sql ) ) die('Failed Query');
		return $result->fetch_assoc();
	}
}
?>
