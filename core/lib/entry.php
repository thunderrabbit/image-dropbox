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
			throw new Exception('no entry id to load');
	
		$sql = sprintf('SELECT * FROM entries WHERE id=%d', $id);
		if(!$result = $this->db->query($sql))
			throw new Exception('error in select query');
		$this->data = $result->fetch_assoc();
		if(is_null($this->data))
			throw new Exception('empty result set');

		$this->tags = new Tags($this->db, $id);
	}

	public function update($field, $value)
	{
		$value = $this->db->safe($value);
		if(!is_set($this->data[$field]) || $this->data[$field] != $value)
			$this->data_updates[$field] = $value;
	}

	public function get($field)
	{
		return (isset($this->updates[$field])) ? 
			$this->updates[$field] : $this->data[$field];
	}

	public function save()
	{
		if(count($this->updates) < 1)
			throw new Exception('no changes');

		if(is_null($this->id)) {
			$sql = sprintf('INSERT INTO entries (%s) VALUES (%s)',
				implode(',', array_keys($this->updates)),
				implode(',', $this->updates));
			if(!$this->db->query($sql))
				throw new Exception('error in insert query');
		} else {
			foreach($this->updates as $key => $val) {
				$updates[] = $key . "='" . $val . "'";
				update_hook($this->id, $key, $this->data[$key], $val);
			}
			$sql = sprintf('UPDATE entries SET %s WHERE id=%d', 
					implode(',', $updates), $this->id);
			if(!$this->db->query($sql))
				throw new Exception('error in update query');
		}

		if(!is_null($this->tags))
			$this->tags->save();
	}

	public function update_tags($tags)
	{
		$arr = explode(',', $tags);
		$this->tags->update($arr);
	}

	public function check_pass($password)
	{
		if(sha1($password) != $this->data['password'])
			throw new Exception('invalid password');
	}
}

?>
