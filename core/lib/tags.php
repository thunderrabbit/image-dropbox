<?php

class Tags {
	private $db;
	private $id;
	private $list;
	private $add;
	private $del;

	private function clean(&$array)
	{
		foreach($array as &$tag) {
			$tag = trim($tag);
			$tag = str_replace(' ', '_', $tag);
			$tag = $this->db->safe($tag);
		}
	}

	public function updates()
	{
		return (count($this->add) > 0 || count($this->del) > 0);
	}

	private function associate($tag)
	{
		$sql = sprintf("select id from tags where name='%s'", $tag);
		if(!$result = $this->db->query($sql))
			throw new DBException('associate - error in select query');

		if($result->num_rows != 0) {
			$row = $result->fetch_array();
			$id = $row[0];
			$sql = sprintf('update tags set date=UNIX_TIMESTAMP() where id=%d', $id);
			if(!$this->db->query($sql))
				throw new DBException('associate - error in update query');
		} else {
			$sql = sprintf("insert into tags (name,date) values ('%s',UNIX_TIMESTAMP())", $tag);
			if(!$this->db->query($sql))
				throw new DBException('associate - error in insert query');
			$id = $this->db->insert_id;
		}
		$result->free();

		$sql = sprintf('insert into tagmap (tag,entry) values (%d,%d)', $id, $this->id);
		if(!$this->db->query($sql))
			throw new DBException('associate - error in insert query');
	}

	private function disassociate($tag)
	{
		// remove tagmap entry (since we already asked for the tag ids should
		// really store that data for use here
		$sql = sprintf("select id from tags where name='%s'", $tag);
		if(!$result = $this->db->query($sql))
			throw new DBException('error in select query');
		$row = $result->fetch_assoc();
		$result->free();
		
		$sql = sprintf('delete from tagmap where tag=%d and entry=%d', $row['id'], $this->id);
		if(!$this->db->query($sql))
			throw new DBException('error in delete query');

		$sql = sprintf('select * from tagmap where tag=%d', $row['id']);
		if(!$result = $this->db->query($sql))
			throw new DBException('error in select query');
	
		if($result->num_rows == 0) {
			$sql = sprintf('delete from tags where id=%d', $row['id']);
			if(!$this->db->query($sql))
				throw new DBException('error in delete query');
		}
		$result->free();
	}

	public function __construct($db, $id = NULL)
	{
		$this->db = $db;
		$this->id = $id;
		$this->list = array();
		$this->add = array();
		$this->del = array();
		if(!is_null($id))
			$this->load($id);
	}

	public function load($id)
	{
		if(is_null($id))
			throw new DBException('no entry id to load tags for');

		$sql = sprintf("SELECT t.name FROM tags t, tagmap m WHERE m.entry=%d &&
						t.id=m.tag ORDER BY t.name", $id);
		if(!$result = $this->db->query($sql))
			throw new DBException('error in tag select query');

		while($row = $result->fetch_assoc())
			$this->list[] = $row['name'];

		$result->free();

		$this->old_string = (count($this->list) > 1) ? implode(',', $this->list)
				: $this->list[0];
	}

	public function update(Array $new)
	{
		$this->clean($new);
		
		$this->new_string = (count($new) > 1) ? implode(',', $new) : $new[0];
		$this->add = array_diff($new, $this->list);
		$this->del = array_diff($this->list, $new);
	}

	public function save()
	{
		foreach($this->add as $tag)
			$this->associate($tag);

		foreach($this->del as $tag)
			$this->disassociate($tag);

		update_hook($this->id, 'tags', $this->new_string, $this->old_string);
	}
}

?>
