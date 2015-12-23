<?php
	// check if someone is logged in
	if(isset($_SESSION['valid_user'])){
		$user = $_SESSION['valid_user'];
?>
        <div id="main_body">
            <div id="content"> <h4 align="left"> Add Company Details </h4>
                <div class="formcontainer">
                    <form action="addcompany.php" method="post">
                    	Company Name: <input name="companyname" type="text" value="" size="30" maxlength="50" /><br /><br />                      Join Date: <input name="joindate" id="joindate" type="text" 
                        value="YYYY-MM-DD"  
                        size="30" maxlength="50" /><br /><br />
                        Branch Address: <input name="branchaddr" type="text" value="" size="30" maxlength="50" /><br /><br />
                        Longitude: <input name="longitude" type="text" value="00.000000" size="30" maxlength="50" /><br /><br />
                        Latitude: <input name="latitude" type="text" value="00.000000" size="30" maxlength="50" /><br /><br />
                        Business Type: <input name="type" type="text" value="" size="30" maxlength="50" /><br /><br />
                        Opening Hours: <input name="ophours" type="text" value="00:00:00" size="30" maxlength="50" /><br /><br />
                        Closing Hours: <input name="clhours" type="text" value="00:00:00" size="30" maxlength="50" /><br /><br />
                        Room Number: <input name="roomnum" type="text" value="" size="30" maxlength="50" /><br /><br />
                        Max Capacity: <input name="maxcap" type="text" size="30" maxlength="50" /><br /><br />
                        <input type="submit" value="Add Company Details" size="15">
                    </form>
                    </div>
            </div>            
        </div>	
	
<?php } else { 
		// if not logged in show this page
		?>

        <div id="main_body">
            <div id="content"> <h4 align="left"> You aren't logged in. </h4>
                <div id="register">
                    Please log in to add rooms.
                    </div>
            </div>            
        </div>

<?php } ?>
