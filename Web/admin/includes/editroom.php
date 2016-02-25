<?php
	// for calling functions
	require_once 'functions/admin_fns.php';
	
	// check if someone is logged in
	if(isset($_SESSION['valid_user'])){
		$user = $_SESSION['valid_user'];
		
		// connect to database n get the db handler
		$conn = db_connect();
		
		//if roomid POST is set
		if(isset($_POST['roomid'])){
			
			$rid = $_POST['roomid'];
			// delete room
			$sql = "DELETE FROM room where room_id = '$rid'";
			
			  if($db->query($insert_room) === TRUE){
				echo "<h4 align='"'left'"'>Successfully deleted room.</h4>" . "<br>";
			  }else {
				echo mysqli_error($db);
			  }
			
		}else if(isset($_POST['room']) && isset($_POST['company']) && isset($_POST['branch'])){
			
			$rid = $_POST['room'];
			$cid = $_POST['company'];
			$bid = $_POST['branch'];
					
			// get company details from db					 
			$cquery = "SELECT company_name cname, join_date jdate from company where company_id = '$cid'";			 
			
			if(!$result = $conn->query($cquery)){
				die('There was an error running the query 1 ['. $conn->error .']');
			}else {	
				$row = $result->fetch_assoc();
				$name = $row['cname'];
				$jdate = $row['jdate'];
			}
			
			// get branch details from db
			$bquery = "SELECT branch_address as baddr, longitude as longi, type as t, 
						opening_hours as ophours, closing_hours as clhours,	
						latitude as lat from branch where branch_id = '$bid'";			 
				
			if(!$result = $conn->query($bquery)){
				die('There was an error running the query 2['. $conn->error .']');
			}else {	
				$row = $result->fetch_assoc();
				$lat = $row['lat'];
				$lon = $row['longi'];
				$addr = $row['baddr'];
				$type = $row['t'];
				$ohours = $row['ophours'];
				$chours = $row['clhours'];
			}
					
			// get room details from database		
			$rquery = "SELECT room_number rnum, max_capacity max from room
						where room_id = '$rid'";			 
			
			if(!$result = $conn->query($rquery)){
				die('There was an error running the query 3 ['. $conn->error .']');
			}else {	
				$row = $result->fetch_assoc();
				$room = $row['rnum'];
				$max = $row['max'];
			}
			
			
			// fill the forms with database values with respect to their ids
?>
        <div id="main_body">
            <div id="content"> <h4 align="left"> Edit Company Details </h4>
                <div class="formcontainer">
                    <form action="editcompany.php" method="post">
                    	Company Name: <input name="companyname" type="text" value="<?php echo $name; ?>" size="30" maxlength="50" /><br /><br />
                        Join Date: <input name="joindate" type="text" value="<?php echo $jdate; ?>" size="30" maxlength="50" /><br /><br />
                        Branch Address: <input name="branchaddr" type="text" value="<?php echo $baddr; ?>" size="30" maxlength="50" /><br /><br />
                        Longitude: <input name="longitude" type="text" value="<?php echo $lon; ?>" size="30" maxlength="50" /><br /><br />
                        Latitude: <input name="latitude" type="text" value="<?php echo $lat; ?>" size="30" maxlength="50" /><br /><br />
                        Business Type: <input name="type" type="text" value="<?php echo $type; ?>" size="30" maxlength="50" /><br /><br />
                        Opening Hours: <input name="ophours" type="text" value="<?php echo $ohours; ?>" size="30" maxlength="50" /><br /><br />
                        Closing Hours: <input name="clhours" type="text" value="<?php echo $chours; ?>" size="30" maxlength="50" /><br /><br />
                        Room Number: <input name="roomnum" type="text" value="<?php echo $room; ?>" size="30" maxlength="50" /><br /><br />
                        Max Capacity: <input name="maxcap" type="text" value="<?php echo $max; ?>" size="30" maxlength="50" /><br /><br />
                        <input type="submit" value="Edit Changes" size="15">
                    </form>
                    </div>
            </div>            
        </div>	
	
<?php 	
		}
	} else { ?>
        <div id="main_body">
            <div id="content"> <h4 align="left"> You aren't logged in. </h4>
                <div id="register">
                    Please log in to edit rooms.
                    </div>
            </div>            
        </div>

<?php } ?>
