<?php

	require_once 'admin/functions/admin_fns.php';
    
	
	$db = db_connect();
	
	$input = $_GET['input'];
	
	// if there are numbers in the search, get results from branch address, else from company name
	// from stackOverFlow - author - Martin Geisler ( just the string comparison function)
	if(strcspn($input, '0123456789') != strlen($input)){
		// if branch address matches 
		$sql = "SELECT c.company_id cid, c.company_name cname, b.branch_id bid, b.branch_address baddr, 
		 		r.room_id rid, r.room_number rnum FROM company AS c INNER JOIN branch b 
				on b.company_id = c.company_id INNER JOIN room as r on r.branch_id = b.branch_id 
				WHERE b.branch_address LIKE '%$input%'";
	}else{
		// if company name matches
		$sql = "SELECT c.company_id cid, c.company_name cname, b.branch_id bid, b.branch_address baddr, 
		 		r.room_id rid, r.room_number rnum FROM company AS c INNER JOIN branch b 
				on b.company_id = c.company_id INNER JOIN room as r on r.branch_id = b.branch_id 
				WHERE c.company_name LIKE '%$input%'";
	}
	
	$rows = $db->query($sql);
	
	// check if the query returns nothing
	if (!$rows) {
    	die('Invalid query 1:  ' . mysqli_error($db));
	}
	/*else{
			echo "success";
	}*/
	
	// include the header file
	include 'includes/header.php';	
?>
         <div id="main_body">
            
        <?php 
		if ($rows->num_rows > 0) {
		?>	
		<div id="content"> <h2> Search Results </h2>
            
            <div align="center" style="margin-top:10px">
                    
            	<table border="0" width="60%" cellspacing="0" cellpadding="4">
                        <tr  bgcolor="#580fa3"><th>Company Name</th>
                        <th colspan="2">Address</th>
                        <th>Room Name</th>
                        <th>Map</th>
                        </tr>	
			
		<?php	
			// output data of each row
			while($row = $rows->fetch_assoc()) {
				?> <tr bgcolor="#a96ee5"><td> <?php
				//"id: " . $row["cid"]. " - Name: " . 					
				echo $row["cname"]; 
				?></td><td colspan="2"><?php echo $row["baddr"];
				// " ". $row["rid"];
				?></td><td><?php echo $row["rnum"];
				?></td><td><form action="map.php" method="POST">
                 <input type="hidden" name="company" value="<?php echo $row["cid"];?>">
                 <input type="hidden" name="branch" value="<?php echo $row["bid"];?>">
                 <input type="hidden" name="room" value="<?php echo $row["rid"];?>">
                 <input type="submit" value="Map the address"></form>
               <?php /* </td><td><input type="button" title="Delete" value="<?php echo $row["rid"]; ?>"> */ ?>
                </td></tr> <?php
			}
				
			// close the database connection	
			mysqli_free_result($rows);
			mysqli_close($db);
		?>
		</table></div>	
              </div>            
        </div>	
    			
    	</div>
		<?php	
			
		}else { 
		?>
		<div id="content"> <h2> Sorry, there aren't any matches. </h2>	</div>        
        <?php	
		}
		?> 
		
      
</div>

<?php 
	// include the footer file
	include 'includes/footer.php';
?>
