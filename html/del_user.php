<!--
		This script aims to delete a user from the database.
		This is not meant to be accessed by a normal user. Only admins should have access to this functionnality.
		The information about the user to be deleted is transmitted by a get method from the previous page (all_users.php)
-->

<html>
	<head>
	</head>
	<body>
		<?php
		/**********************************************************************************************************************************************/
		/*							Authentication checks								      */
		/**********************************************************************************************************************************************/

			// Checking whether the user is logged in...
			session_start();
			echo "test";
			if(!isset($_SESSION['login_user'])){
			// ...If not, redirect to the login page
				header("location: login.php");
			}
			else{
			// In the case that he isn't an admin user, show a popup error and go back to the previous page.
				if (isset($_SESSION['admin'])){
					$username = filter_var($_SESSION['login_user'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_SPECIAL_CHARS);
				}
			else{
					echo "<script type='text/javascript'>alert('Unauthorized');history.go(-1);</script>";
				}
			}

			try{
				// We get the id of the user we want to delete				
				$id = filter_var($_GET['id'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_SPECIAL_CHARS);

				// Create (connect to) SQLite database in file
				$dbconn = new PDO('sqlite:/var/www/databases/database.sqlite');
				// Disabling emulated prepared statements
				$dbconn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				// Set errormode to exceptions
				$dbconn->setAttribute(PDO::ATTR_ERRMODE,
						    PDO::ERRMODE_EXCEPTION);

				// Deleting the row having the id of the user
				// and using prepared statement against SQL injections
				$delete = $dbconn->prepare("DELETE FROM users WHERE id = :id");
				$delete->execute(array('id' => $id));

				// Close file db connection
				$dbconn = null;
			}
			catch(PDOException $e) {
				// Print PDOException message
				echo $e->getMessage();
			}

			// Show a popup message telling that the operation has been done correctly and redirecing to 'all_users.php'
			echo "<script type='text/javascript'>alert('User correctly deleted');</script>";
			header("location: all_users.php");
		?>
	</body>
</html>
