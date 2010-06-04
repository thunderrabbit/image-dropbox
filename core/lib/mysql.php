<?php

/* Create a child class of mysqli */
class dropbox_mysqli extends mysqli
{
	public function safe( $string ) {
		return $this->real_escape_string( $string );
	}

	public function query($query)
	{
		debug_hook($query);
		return parent::query($query);
	}

	public function select( $query ) {
		// may add hook in here for memcache support
		if ( $results = $this->query( $query ) ) {
			$ret = $results->fetch_assoc();
			$results->free();
		} else {
			$ret = false;
		}
		return $ret;
	}

	public function update( $query ) {
		// may migrate all update queries to this function to add memcache support
	}

	public function insert( $query ) {
		// may migrate all insert queries to this function to add memcache support
	}

	public function exists( $query ) {
		if ( $result = $this->query( $query ) ) {
			$ret = ( $result->num_rows > 0 );
		} else {
			$ret = false;
		}
		return $ret;
	}
}

?>
