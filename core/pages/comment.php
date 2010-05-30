<?php

if(DB_COMMENTS) {
	$id = intval( $entry );

	if ( $_POST ) {
		if ( $_POST['verify'] == $_SESSION['verify.' . $id] ) {
			$name = ( $_POST['name'] ) ? strval( $_POST['name'] ) : 'anonymous';
			$content = strval( $_POST['content'] );
			$host = gethostbyaddr( $_SERVER['REMOTE_ADDR'] ) . ' (' . $_SERVER['REMOTE_ADDR'] . ')';

			if ( $content ) {
				$sql = sprintf( "insert into comments (entry,name,content,ip,date) values (%d,'%s','%s','%s',%d)",
								$id, $name, $content, $host, time() );
				if ( !$db->query( $sql ) )
					die( "error in query" );
			}
		}
	} 
}
header("Location: DB_LOC/view/$id/");

?>
