<?php
	// include function files for this application
	require_once('functions/admin_fns.php');
	
	//create short variable names for POST captures
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
	
	$rid = $_POST['room'];
	$cid = $_POST['company'];
	$bid = $_POST['branch'];
	
	// start session which may be needed later
	// start it now because it must go before headers
	session_start();
	
	// connect to database
	$db = db_connect();
	
	try {
		// check forms filled in
		if (!filled_out($_POST)) {
			throw new Exception('You have not filled the form out correctly. Please go back and try again.');
		}
				
		// update database functions 
		updateCompany($db, $company, $date, $cid);
		
		updateBranch($db, $branch, $type, $opening_hours, $closing_hours, $longitude, $latitude, $bid);
		
		updateRoom($db, $room, $max_cap, $rid);
		
		// success head to success page
		$url = 'index.php?page=addsuccess';
		ob_end_clean( );
		header("Location: $url");
		exit( );
		
	}
	catch (Exception $e) {
		// print error
		echo $e->getMessage();
		// exit
		exit;
	}
?>
