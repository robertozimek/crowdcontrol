<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/styles.css" rel="stylesheet" type="text/css" />
<title>::.Crowd Control Project.::</title>
</head>
<body>
    <div id="header_bg">
    	<div id="header">
        	<div id="banner">
                <div id="login">
                	<?php // check if someone is logged in
						if(isset($_SESSION['valid_user'])) {
							$user = $_SESSION['valid_user']; ?>						  
							<a href="#">You are logged in as <?php echo $user; ?></a>
                            <a href="admin/index.php">Admin Page</a>
                        	<a href="admin/logout.php">Sign Out</a>
                    <?php }else{ ?>    		
							<a href="admin/index.php?page=login">Log In</a>
                    <?php } ?>    
                </div>
            </div>
           
            <div id="menu">
            
            	<ul>
            		<li><a href="index.php">Home</a></li>
          			<li><a href="index.php?page=project">Project</a></li>              
              		<li><a href="index.php?page=business">Businesses</a></li>
                    <li><a href="index.php?page=git">Git Hub Page</a></li>
                    <li><a href="index.php?page=about">About Us</a></li>
                </ul>   
            </div> 
              
        </div>  
    </div>
