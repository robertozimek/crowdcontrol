<?php 
		// include database functions
		include 'admin/functions/admin_fns.php';
        // connect to database
		$db = db_connect();
		 // select company and branch that has room number
		 $sql = "SELECT c.company_id cid, c.company_name cname, b.branch_id bid, b.branch_address baddr, 
		 		r.room_id rid, r.room_number rnum FROM company AS c INNER JOIN branch b 
				on b.company_id = c.company_id INNER JOIN room as r on r.branch_id = b.branch_id ";
		
		$rows = $db->query($sql);
			
		if (!$rows) {
    		die('Invalid query 1:  ' . mysqli_error($db));
		}
		/*else{
			echo "success";
		}*/		
		?>
         <div id="main_body">
            <div id="content"> <h2> All Businesses </h2>
            
            <div align="center" style="margin-top:10px">
                    
            	<table border="0" width="60%" cellspacing="0" cellpadding="4">
                        <tr  bgcolor="#330066"><th bgcolor="#330066">Company Name</th>
                        <th colspan="2">Address</th>
                        <th>Room Name</th>
                        <th>Map</th>
                        </tr>
        <?php 
		if ($rows->num_rows > 0) {
			// output data of each row
			while($row = $rows->fetch_assoc()) {
				?> <tr bgcolor="#220055"><td> <?php 					
				echo $row["cname"]; 
				?></td><td colspan="2"><?php echo $row["baddr"];
				// " ". $row["rid"];
				?></td><td><?php echo $row["rnum"];
				// input hidden to send POSTs to next page
				?></td><td><form action="map.php" method="POST">
                 <input type="hidden" name="company" value="<?php echo $row["cid"];?>">
                 <input type="hidden" name="branch" value="<?php echo $row["bid"];?>">
                 <input type="hidden" name="room" value="<?php echo $row["rid"];?>">
                 <input type="submit" value="Map the address"></form>
                </td></tr> <?php
			}
				
			// close database connection	
			mysqli_free_result($rows);
			mysqli_close($db);
		}
		?> 
		</table></div>	
            </div>            
        </div>	
    			
    	</div>
</div>
