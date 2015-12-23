<?php
	
	// start session
	session_start();	
	
	// check if someone is already logged in
	if(isset($_SESSION['valid_user'])){
		$user = $_SESSION['valid_user'];
	}

	
	// check if it is asking for a page else its main page
	if(isset($_GET['page'])){
		$page = $_GET['page'];
	}else{
		$page = 'main';
	}
	
	//include html header showing page
	include 'includes/header.php';
	
	// call respective pages, if not the main_body page
	if( $page == 'business') {
		include 'includes/businesses.php';
	}else if( $page == 'project') {
		include 'includes/project.php';
	}else if( $page == 'about' ){
		include 'includes/aboutus.php';
	}else if( $page == 'git' ){
		include 'includes/git.php';	
	}else{
		include 'includes/main_body.php';
	}
	
	// include footer file
	include 'includes/footer.php';
	
	

?>
