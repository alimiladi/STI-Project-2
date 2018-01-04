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
		if(!empty($_POST['username']) && !empty($_POST['password'])){

			// Store in two local variables.
			$username = $_POST['username'];
			$password = $_POST['password'];


			// Create (connect to) SQLite database in file
			$dbconn = new PDO('sqlite:/var/www/databases/database.sqlite');
			// Disabling emulated prepared statements
			$dbconn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			// Set errormode to exceptions
			$dbconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			// We get the user's data stored in the DB and
			// we protect prepared statement against SQL injections
			$result = $dbconn->prepare("SELECT * FROM users WHERE username = :username");
			$result->execute(array('username' => $username));
			    
			// We get the values of each row of the users table in the DB
			foreach($result as $row) {      
				$hashStored = $row['password'];
				$activeStored = $row['active'];
				$adminStored = $row['admin'];
			}

			// Close file db connection
			$dbconn = null;
			
			// If hashes correspond we can go further
			if(hash('sha256', $password) == $hashStored && $activeStored == 1) {


				// Case where the credentials are correct and the user is active.
				$_SESSION['login_user'] = $username;



/**********************************************************************************************************************************************/
/*							Redirections									      */
/**********************************************************************************************************************************************/

				// Redirect consequently.
				if($adminStored == 1){
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
