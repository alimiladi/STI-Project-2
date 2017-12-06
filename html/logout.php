<!--
	This script is aimed to close the session and redirect the user to the login page.
	It is not meant to be accessed by any user directly but is called behind the scenes when he hits the button 'logout' in any page.
-->

<?php
/**********************************************************************************************************************************************/
/*							Authentication checks								      */
/**********************************************************************************************************************************************/
	
	// Checking whether the user is logged in...
	session_start();

	// ...If not, redirect to the login page
	if(!isset($_SESSION['login_user'])){
		header("location: login.php");
	}
	else{
	// Even logged in, any user doesn't have the access right s to this page.
	// An error message popup is shown and he get's redirected to the previous page.  
		echo "<script type='text/javascript'>alert('Unauthorized');history.go(-1);</script>";
	}
	// Destroys the session and the session variables, and redirect to the login page. 
	if(session_destroy()){
		//redirect to login page
		header("Location: login.php");
	}
?>
