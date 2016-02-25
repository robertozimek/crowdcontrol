<?php
	// include function files for this application
	require_once('functions/admin_fns.php');
	
	session_start();
	$old_user = $_SESSION['valid_user'];
	
	// store to test if they *were* logged in
	unset($_SESSION['valid_user']);
	
	$result_dest = session_destroy();
	
	
	if (!empty($old_user)) {
		if ($result_dest) {
			
			// if they were logged in and are now logged out
			// send them to logged out page
			
			$url = 'index.php?page=logout';
			ob_end_clean( );
			header("Location: $url");
			exit( );
			
		} else {
			// they were logged in and could not be logged out
		
			$url = 'index.php?page=error4';
			ob_end_clean( );
			header("Location: $url");
			exit( );
		}
	} else {
		// if they weren't logged in but came to this page somehow
		
		$url = 'index.php?page=error5';
		ob_end_clean( );
		header("Location: $url");
		exit( );
	}
	
?>
