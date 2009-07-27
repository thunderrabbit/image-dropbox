<?php

/* Create a child class of mysqli */
class dropbox_mysqli extends mysqli
{
	function safe( $string ) {
		return $this->real_escape_string( $string );
	}

	function select( $query ) {
		if ( $results = $this->query( $query ) ) {
			$ret = $results->fetch_assoc();
			$results->free();
		} else {
			$ret = false;
		}
		return $ret;
	}

	function exists( $query ) {
		if ( $result = $this->query( $query ) ) {
			$ret = ( $result->num_rows > 0 );
		} else {
			$ret = NULL;
		}
		return $ret;
	}
}

?>
