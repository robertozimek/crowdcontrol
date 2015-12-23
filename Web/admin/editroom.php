<?php
	// needed to call functions 
	require_once 'functions/admin_fns.php';
	
	if(isset($_SESSION['valid_user'])){
		$user = $_SESSION['valid_user'];
		
		$conn = db_connect();
		
		if(isset($_POST['roomid'])){
			
			$rid = $_POST['roomid'];
			
			// delete room
			$sql = "DELETE FROM room where room_id = '$rid'";
			
			  if($conn->query($sql) === TRUE){
				// if successful, take to the success page on index.php  
				$url = 'index.php?page=addsuccess';
				ob_end_clean( );
				header("Location: $url");
				exit();
			  }else {
				echo mysqli_error($conn);
			  }
			
		}else if(isset($_POST['room']) && isset($_POST['company']) && isset($_POST['branch'])){
			
			$rid = $_POST['room'];
			$cid = $_POST['company'];
			$bid = $_POST['branch'];
					
			// get all info from company					 
			$cquery = "SELECT company_name cname, join_date jdate from company where company_id = '$cid'";			 
			
			if(!$result = $conn->query($cquery)){
				die('There was an error running the query 1 ['. $conn->error .']');
			}else {	
				$row = $result->fetch_assoc();
				$name = $row['cname'];
				$jdate = $row['jdate'];
			}
			
			// get all info from branch
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
			
			// get all info from room
			$rquery = "SELECT room_number rnum, max_capacity max from room
						where room_id = '$rid'";			 
			
			if(!$result = $conn->query($rquery)){
				die('There was an error running the query 3 ['. $conn->error .']');
			}else {	
				$row = $result->fetch_assoc();
				$room = $row['rnum'];
				$max = $row['max'];
			}
			
			// populate the forms with database info relating to their ids
			
?>
        <div id="main_body">
            <div id="content"> <h4 align="left"> Edit Company Details </h4>
                <div class="formcontainer">
                    <form action="editcompany.php" method="post">
                    	Company Name: <input name="companyname" type="text" value="<?php echo $name; ?>" size="30" maxlength="50" /><br /><br />
                        Join Date: <input name="joindate" type="text" value="<?php echo $jdate; ?>" size="30" maxlength="50" /><br /><br />
                        Branch Address: <input name="branchaddr" type="text" value="<?php echo $addr; ?>" size="30" maxlength="50" /><br /><br />
                        Longitude: <input name="longitude" type="text" value="<?php echo $lon; ?>" size="30" maxlength="50" /><br /><br />
                        Latitude: <input name="latitude" type="text" value="<?php echo $lat; ?>" size="30" maxlength="50" /><br /><br />
                        Business Type: <input name="type" type="text" value="<?php echo $type; ?>" size="30" maxlength="50" /><br /><br />
                        Opening Hours: <input name="ophours" type="text" value="<?php echo $ohours; ?>" size="30" maxlength="50" /><br /><br />
                        Closing Hours: <input name="clhours" type="text" value="<?php echo $chours; ?>" size="30" maxlength="50" /><br /><br />
                        Room Number: <input name="roomnum" type="text" value="<?php echo $room; ?>" size="30" maxlength="50" /><br /><br />
                        Max Capacity: <input name="maxcap" type="text" value="<?php echo $max; ?>" size="30" maxlength="50" /><br /><br />
                        <input type="hidden" name="company" value="<?php echo $cid;?>">
                 		<input type="hidden" name="branch" value="<?php echo $bid;?>">
                 		<input type="hidden" name="room" value="<?php echo $rid;?>">
                        <input type="submit" value="Edit Changes" size="15">
                    </form>
                    </div>
            </div>            
        </div>	
	
<?php 	
		}
	} else { // if not logged in show the page below
		?>
        <div id="main_body">
            <div id="content"> <h4 align="left"> You aren't logged in. </h4>
                <div id="register">
                    Please log in to edit rooms.
                    </div>
            </div>            
        </div>

<?php } ?>
