<!--
		This is the langing page of the app. The page distibuted when a user types 'http://localhost/' in his web browser.
		This page checks if the user is connected and redirects him to it's home page depending on his type if his session is
		effectively active. It redirects him to the login page otherwise.

-->
<?php
	session_start();
	if(!isset($_SESSION['login_user']))
	{
		header("location: login.php");
	}
	else
	{
		try{
			// Create (connect to) SQLite database in file
			$dbconn = new PDO('sqlite:/var/www/databases/database.sqlite');
			// Set errormode to exceptions
			$dbconn->setAttribute(PDO::ATTR_ERRMODE,
			PDO::ERRMODE_EXCEPTION);

			$result = $dbconn->query("SELECT COUNT(*) as count FROM users WHERE username = '$username' AND admin = 1");
			$count = $result->fetchColumn();

			if($count == 1)
			{
			  $_SESSION['admin'] = true;
			  header("location: admin_home.php");
			}
			else{
			   header("location: user_home.php");
			}

			// Close file db connection
			$dbconn = null;
		}

		catch(PDOException $e) {
			// Print PDOException message
			echo $e->getMessage();
		}

		$username = $_SESSION['login_user'];
		header("location: login.php");
	}
?>
