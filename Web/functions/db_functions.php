<?php
  // password hash function called
	require_once('password_compat-master/lib/password.php');

	// connect to database and return the handler
	function db_connect() {
		// have to put personal hostname, username, password, databasename to run
		$db = new mysqli('HOSTNAME', 'USERNAME', 'PASSWORD', 'DATABASENAME');

		if ($db->connect_errno) {
			die("Connection failed: " . $db->connect_error);
		}else{
			//echo "Connection Successful!";
			return $db;
		}		
	}	

?>
