<!--
	This script aims to redirect the user to it's correct landing page depending on he's access rights.
	The login formular gets posted to this script which checks the username/password and access rights against the data stored in the DB.
	It then redirects the user to the correct home page accordingly.
-->

<?php

/**********************************************************************************************************************************************/
/*							Authentication checks								      */
/**********************************************************************************************************************************************/

	session_start();
	// Checking whether the user is logged in...
	if(!isset($_SESSION['login_user'])){
	// ...If not, redirect to the login page
		header("location: login.php");
	}
	else{
	// Otherwise show a popup error message and go back to previous page.
		echo "<script type='text/javascript'>alert('Unauthorized');history.go(-1);</script>";

	}

	try {
		// Get the posted data from the formular in the variable $_POST.
		if(!empty($_POST["username"]) && !empty($_POST["password"])){

			// Store in two local variables.
			$username = $_POST["username"];
			$password = $_POST["password"];


			// Create (connect to) SQLite database in file
			$db = new PDO('sqlite:/var/www/databases/database.sqlite');

			// Set errormode to exceptions
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			// Look in the DB if the username/password exist, are correct, and that the user is active.
			$result = $db->query("SELECT COUNT(*) as count FROM users WHERE username = '$username' AND password = '$password' AND active= 1");

			$count = $result->fetchColumn();

			// $count takes values 0 or 1 depending on whether the credentials are correct and the user is active.
			if ($count == 1){

				// Case where the credentials are correct and the user is active.
				$_SESSION['login_user'] = $username;

				// Find out whether he is admin.
				$result = $db->query("SELECT COUNT(*) as count FROM users WHERE username = '$username' AND admin= 1");
				$count = $result->fetchColumn();


/**********************************************************************************************************************************************/
/*							Redirections									      */
/**********************************************************************************************************************************************/

				// Redirect consequently.
				if($count == 1){
					$_SESSION['admin'] = true;
					header("location: admin_home.php");
				}
				else{
					header("location: user_home.php");
				}
			}

			// Case where the user is not active or username/password does nor match.
			else{
				$_SESSION['wrong_password'] = true;
				header("location: login.php");
			}

		}

		// Case where there wasn't any posted formular.
		else{
			$_SESSION['wrong_password'] = true;
			header("location: login.php");
		}
	}
	catch(PDOException $e) {
		// Print PDOException message
		echo $e->getMessage();
	}

?>
