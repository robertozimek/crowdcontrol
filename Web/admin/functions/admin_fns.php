<?php
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
	
	// get RECORD DETAILS
	function getRecord($db) {
		$query = "SELECT c.company_id cid, c.company_name cname, b.branch_id bid, b.branch_address baddr, 
		DISTINCT r.room_id rid, r.room_num rnum FROM company AS c 
		INNER JOIN branch b on b.company_id = c.company_id INNER JOIN room as r.branch_id = b.branch_id ";	
	}

	// adrian's insert functions below
	function addCompany($db, $company, $date){
      $insert_comp = "INSERT INTO company (`company_name`, `join_date`) VALUES ('$company', '$date')";
      if($db->query($insert_comp) === TRUE){
        echo "Successfully inserted '$company' <br>";
	  }else {
        echo mysqli_error($db);
      }
    }

    function addBranch($db, $company, $branch, $type, $opening_hours, $closing_hours, $longitude, $latitude){
      $insert_branch = "INSERT INTO branch (`company_id`, `branch_address`, `type`, `opening_hours`, `closing_hours`, `longitude`, `latitude`)
                        VALUES ((SELECT `company_id` FROM company WHERE `company_name` = '$company'), '$branch', '$type',
                        '$opening_hours', '$closing_hours', '$longitude', '$latitude')";
      if($db->query($insert_branch) === TRUE){
        echo "Successfully inserted '$branch' <br />";
	  }else {
        echo mysqli_error($db) . "<br />";
      }
    }

    function addRoom($db, $company, $branch, $room, $max_cap){
      $sub_query = "SELECT c.company_id, b.branch_id FROM company AS c
                    INNER JOIN branch b on b.company_id = c.company_id
                    WHERE b.branch_address = '$branch' AND c.company_name = '$company'";
      $results = $db->query($sub_query);
      $ids = $results->fetch_assoc();
      $branch_id = $ids['branch_id'];

      $rand = genRandom();
      $hash = password_hash($rand, PASSWORD_DEFAULT); 

      $insert_room = "INSERT INTO room (`branch_id`, `room_number`, `max_capacity`, `secret_key`)
                      VALUES ('$branch_id', '$room', '$max_cap', '$hash')";
      if($db->query($insert_room) === TRUE){
        echo "Successfully inserted '$room'" . "<br>";
        echo "Your Pi's password is " . $rand . ". Keep it safe and secure. The Pi needs it to submit counts" . "<br>";
      }else {
        echo mysqli_error($db) . "<br />";
      }
    }
	
	// functions for update company
	function updateCompany($db, $company, $date, $cid){
		$sql = "UPDATE company SET company_name = '$company', join_date = '$date' WHERE company_id = '$cid'";
		  if($db->query($sql) === TRUE){
        	echo "Successfully updated '$company' <br>";
	  	}else {
       		echo mysqli_error($db);
     	}
	}
	
	// function to update branch
	function updateBranch($db, $branch, $type, $opening_hours, $closing_hours, $longitude, $latitude, $bid){
		$sql = "UPDATE branch SET branch_name = '$branch', type = '$type', opening_hours = '$opening_hours',
				closing_hours = '$closing_hours', longitude = '$longitude', latitude = '$latitude' WHERE branch_id = '$bid'";
		  if($db->query($sql) === TRUE){
        	echo "Successfully updated '$branch' <br>";
	  	}else {
       		echo mysqli_error($db);
     	}
	}
	
	// function to update room
	function updateRoom($db, $room, $max_cap, $rid){
		$sql = "UPDATE room SET room_number = '$room', max_capacity = '$max_cap' WHERE room_id = '$rid'";
		  if($db->query($sql) === TRUE){
        	echo "Successfully updated '$room' <br>";
	  	}else {
       		echo mysqli_error($db);
     	}
	}

    function genRandom(){
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $randomString = '';
      $length = 10;
      for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return $randomString;
    }
	

	function filled_out($form_vars) {
		// test that each variable has a value
		foreach ($form_vars as $key => $value) {
			if ((!isset($key)) || ($value == '')) {
				return false;
			}
		}
		return true;
	}

?>
