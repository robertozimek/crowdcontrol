<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="adminStyles.css" rel="stylesheet" type="text/css" />
		<title>:.:Crowd Control Admin Page:.:</title>
	</head>

	<body>
     		<div id="loggedIn">
            	<?php if(isset($_SESSION['valid_user'])) {
						$user = $_SESSION['valid_user']; ?>
                        						  
						<span> You are logged in as: <?php echo $user; ?> </span>&nbsp;&nbsp;
                        <a href="logout.php">Sign Out</a>
                    <?php } else { ?>    		
						<a href="index.php?page=login">Log In</a>
                <?php } ?>
            </div>
            <div id="header">
            <a href="index.php"><div id="banner">&nbsp;</div></a>
            </div>
            <div id="menu">
            	<ul> <?php if(isset($_SESSION['valid_user'])) { ?> 
                	<li><a href="index.php?page=listroom">List/Edit Room</a></li>
                 	<li><a href="index.php?page=addroom">Add Room</a></li>
                	<!--<li><a href="index.php?page=change">Change Password</a></li> -->
            		<li><a href="index.php">Home</a></li>
                    <li><a href="../index.php">Main Site</a></li>
                    <?php }else{ ?>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="../index.php">Main Site</a></li>
                    <?php } ?>
                </ul>   
            </div>
