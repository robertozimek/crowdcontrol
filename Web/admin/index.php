<?php
	// start the session
	session_start();
	
	if(isset($_SESSION['valid_user'])){
		$user = $_SESSION['valid_user'];
		
		// TO TEST:::::check if session is working 
		//echo "Welcome " . $user . "</br>";
	}
	
	if(isset($_GET['page'])){
		$page = $_GET['page'];
	}else{
		$page = 'main';
	}
	
	// html header showing function
		
	include 'includes/header.php';
	
	// show the respective pages called
	if( $page == 'register') {
		include 'includes/register.php';
	}else if( $page == 'success') {	// change for success login, registering
		include 'includes/success.php';
	}else if( $page == 'addsuccess') {	// change for success login, registering
		include 'includes/addsuccess.php';
	}else if( $page == 'listroom') {	// change for success login, registering
		include 'includes/listroom.php';
	}else if( $page == 'login' ){	
		include 'includes/main_body.php';
	}else if( $page == 'logout' ){
		include 'includes/loggedout.php';
	}else if( $page == 'map' ){
		include 'includes/map.php';
	}else if( $page == 'addroom' ){
		include 'addroom.php';
	}else if( $page == 'edit' ){
		include 'editroom.php';
	}else if( $page == 'error2' ){
		?>
			<p id="error">Either the email address and password entered do not match those on file or </br> you have not yet activated your account.</p>
		<?php
	}else if( $page == 'error3'){
		?>
        	<p id="error">Please try again.</p>
        <?php
	}else if( $page == 'error4'){
		?>
        	<p id="error"> Sorry, you haven't logged out successfully. </p> 
        <?php
	
	}else if( $page == 'error5'){
		?>
        	<p id="error"> You were not logged in, and so have not been logged out.</p>
        <?php
	}else{
		if(isset($_SESSION['valid_user'])){
			include 'includes/success.php';
		}else{
			include 'includes/main_body.php';
		}
	}
	
	// include 
	include 'includes/footer.php';
	
	
?>
