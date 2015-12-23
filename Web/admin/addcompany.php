<?php
	// include function files for this application
	require_once('functions/admin_fns.php');
	
	//create short variable names
	$company = $_POST['companyname'];
	$date = $_POST['joindate'];
	$branch = $_POST['branchaddr'];
	$longitude = $_POST['longitude'];
	$latitude = $_POST['latitude'];
	$type = $_POST['type'];
	$opening_hours = $_POST['ophours'];
	$closing_hours = $_POST['clhours'];
	$room = $_POST['roomnum'];
	$max_cap = $_POST['maxcap'];
	
	// start session which may be needed later
	// start it now because it must go before headers
	session_start();
	
	// get database handle
	$db = db_connect();
	
	try {
		// check forms filled in
		if (!filled_out($_POST)) {
			throw new Exception('You have not filled the form out correctly. Please go back and try again.');
		}
		
		// attempt to register
		// this function can also throw an exception
		addCompany($db, $company, $date);
		
		addBranch($db, $company, $branch, $type, $opening_hours, $closing_hours, $longitude, $latitude);
		
		addRoom($db, $company, $branch, $room, $max_cap);
		
		// send to the main page
		$url = 'index.php?page=addsuccess';
		// clean buffer
		ob_end_clean( );
		// go to the header
		header("Location: $url");
		exit( );
	}
	catch (Exception $e) {
		// print error
		echo $e->getMessage();
		exit;
	}
?>
