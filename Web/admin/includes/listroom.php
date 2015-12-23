<?php
	require_once 'functions/admin_fns.php';
	
	if(isset($_SESSION['valid_user'])){
		$user = $_SESSION['valid_user'];
		
		 $db = db_connect();
		 
		 // get the rooms which have a branch and company
		 $sql = "SELECT c.company_id cid, c.company_name cname, b.branch_id bid, b.branch_address baddr, 
		 		r.room_id rid, r.room_number rnum FROM company AS c INNER JOIN branch b 
				on b.company_id = c.company_id INNER JOIN room as r on r.branch_id = b.branch_id ";
		
		$rows = $db->query($sql);
			

		// check the db return handler	
		if (!$rows) {
    		die('Invalid query 1:  ' . mysqli_error($db));
		}
		/*else{
			echo "success";
		}*/		
		?>
         <div id="main_body">
            <div id="content"> <h4 align="left"> All Companies </h4>
            
            <div align="left">
                    
            	<table border="0" width="100%" cellspacing="0">
                       <?php // populate the tables ?>
                        <tr><th bgcolor="#cccccc">Company Name</th>
                        <th colspan="2" bgcolor="#cccccc">Address</th>
                        <th bgcolor="#cccccc">Room Name</th>
                        <th bgcolor="#cccccc">Edit</th>
                        <th bgcolor="#cccccc">Delete</th>
                        </tr>
        <?php 
		if ($rows->num_rows > 0) {
			// output data of each row
			while($row = $rows->fetch_assoc()) {
				?> <tr bgcolor="#999999"><td> <?php
				//"id: " . $row["cid"]. " - Name: " . 					
				echo $row["cname"]; 
				?></td><td colspan="2"><?php echo $row["baddr"];
				// " ". $row["rid"];
				?></td><td><?php echo $row["rnum"];
				?></td><td>
                <?php /*<input type="button" title="Edit" value="<?php echo $row["cid"]. "-". $row["bid"] . "-". $row["rid"]; ?>"> */?>
                <form action="index.php?page=edit" method="POST">
                 <input type="hidden" name="company" value="<?php echo $row["cid"];?>">
                 <input type="hidden" name="branch" value="<?php echo $row["bid"];?>">
                 <input type="hidden" name="room" value="<?php echo $row["rid"];?>">
                 <input type="submit" value="Edit Info"></form>
                </td><td>
                <form action="index.php?page=edit" method="POST">
                <input type="hidden" name="roomid" value="<?php echo $row["rid"]; ?>">
                <input type="submit" value="Delete room"></form>
                </td></tr> <?php
			}
				
			// free handler and close db	
			mysqli_free_result($rows);
			mysqli_close($db);
		}
		 
		 ?>
        </table></div>	
            </div>            
        </div>	
	
<?php 
	} else { 
		// else show this page if not logged in
		?>
        <div id="main_body">
            <div id="content"> <h4 align="left"> You aren't logged in. </h4>
                <div id="register">
                    Please log in to add rooms.
                    </div>
            </div>            
        </div>

<?php }  ?>
