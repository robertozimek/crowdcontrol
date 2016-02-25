<?php
	// include function files for this application
	require_once('functions/admin_fns.php');
	session_start();
	$_SESSION['valid_user'] = NULL;
	
	if($_SERVER['REQUEST_METHOD']=='POST'){
		
		$db = db_connect();
		
		// validate the email address
		if(!empty($_POST['username'])){
			$username =  mysqli_real_escape_string($db, $_POST['username']);
		}else {
			$username = 'FALSE';
			
			echo '<p class="error">You forgot to enter your email address!</p>';
		}
		
		// validate the password
		if(!empty($_POST['password'])){
			$password = mysqli_real_escape_string($db, $_POST['password']);
		}else {
			$password = 'FAlSE';
			echo '<p class="error">You forgot to enter your email address!</p>';
		}
		
		//echo $email . ' '. $password. '</br>';
		
		if($username && $password) {
			
			// Query the database:
			$sql = "SELECT * FROM user WHERE username='".$username."' AND password='".$password."'";
			
			//$result = mysqli_query($db, $sql);// or trigger_error("Query: $query\n<br />MySQL Error: " .mysqli_error($db));
			$rows = $db->query($sql);
			
			//$row = mysqli_fetch_assoc($rows);
			
			if (!$rows) {
    			die('Invalid query 1:  ' . mysqli_error($db));
			}else{
				echo "success";
			}		
	
			if ($rows->num_rows > 0) {
			// output data of each row
				 //while(
				$row = $rows->fetch_assoc();
					   //) {
			//		echo "id: " . $row["id"]. " - Name: " . $row["name"]. " " . $row["username"]. "<br>" . $row["password"];
				//}
				$_SESSION['valid_user'] = $row["username"];	
				$_SESSION['id'] = $row["id"];
				
				mysqli_free_result($rows);
				mysqli_close($db);
				
				// Redirect the user:
 				$url = 'index.php?page=success';
				// Define the URL.
				ob_end_clean( ); // Delete the buffer.
				header("Location: $url");
				exit( ); // Quit the script.
					
			} else {
				$msg = 'error2';
				
				$url = 'index.php?page=' . $msg;
				// Define the URL.
				ob_end_clean( ); // Delete the buffer.
				header("Location: $url");
				exit( ); // Quit the script.
			}

		} else { // If everything wasn't OK.
			
			$msg = 'error3';
				
			$url = 'index.php?page=' . $msg;
			// Define the URL.
			ob_end_clean( ); // Delete the buffer.
			header("Location: $url");
			exit( ); // Quit the script.
		}
		
		//echo $_SESSION['valid_user'];
		//echo "</br>";
		
		//mysqli_close($db);
	}
			
?>
