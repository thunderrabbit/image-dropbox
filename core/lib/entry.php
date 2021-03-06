<?php

require_once DB_PATH . '/core/lib/tags.php';

class Entry {
	private $db;
	private $id;
	private $data;
	private $updates;
	private $tags;

	public function __construct($db, $id = NULL)
	{
		$this->db = $db;
		$this->id = $id;
		$this->data = array();
		$this->updates = array();
		$this->tags = NULL;
		if(!is_null($id))
			$this->load($id);
	}

	public function load($id)
	{
		if(is_null($id))
			throw new DBException('no entry id to load');
	
		$sql = sprintf("SELECT * FROM " . DB_PREFIX . "entries WHERE id=%d", $id);
		if(!$result = $this->db->query($sql))
			throw new DBException('error in select query');
		$this->data = $result->fetch_assoc();
		if(is_null($this->data))
			throw new DBException('empty result set');

		$this->tags = new Tags($this->db, $id);
	}

	public function update($field, $value)
	{
		$value = $this->db->safe($value);
		if(!isset($this->data[$field]) || $this->data[$field] != $value)
			$this->updates[$field] = $value;
	}

	public function get($field)
	{
		return (isset($this->updates[$field])) ? 
			$this->updates[$field] : $this->data[$field];
	}

	public function save()
	{
		if(count($this->updates) < 1 && (!$this->tags->updates()))
			throw new DBException('no changes');

		if(is_null($this->id)) {
			if(count($this->updates) > 1) {
				$fields = implode(',', array_keys($this->updates));
				$values = implode("','", $this->updates);
			} else {
				$fields = key($this->updates);
				$values = $this->updates[0];
			}
			$sql = sprintf("INSERT INTO " . DB_PREFIX . "entries (%s,date,views) VALUES 
					('%s',UNIX_TIMESTAMP(),0)", $fields, $values);
			if(!$this->db->query($sql))
				throw new DBException('error in insert query ' . $sql );
			$this->id = $this->db->insert_id;
			$update = false;
		} else {
			if(count($this->updates) > 0) {
				foreach($this->updates as $key => $val) {
					$updates[] = $key . "='" . $val . "'";
					update_hook($this->id, $key, $this->data[$key], $val);
				}
				$sql = sprintf("UPDATE " . DB_PREFIX . "entries SET %s WHERE id=%d", 
						implode(',', $updates), $this->id);
				if(!$this->db->query($sql))
					throw new DBException('error in update query');
			}
			$update = true;
		}

		if(!is_null($this->tags))
			$this->tags->save($update);
	}

	public function update_tags($tags)
	{
		$arr = explode(',', $tags);
		$this->tags->update($arr);
	}

	public function check_pass($password)
	{
		if(sha1($password) != $this->data['password'])
			throw new DBException('invalid password');
	}

	public function get_id()
	{
		return $this->id;
	}
}

?>
