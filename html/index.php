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
			$username = filter_var($_POST['username'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_SPECIAL_CHARS);	

			// Create (connect to) SQLite database in file
			$dbconn = new PDO('sqlite:/var/www/databases/database.sqlite');
			// Disabling emulated prepared statements
			$dbconn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			// Set errormode to exceptions
			$dbconn->setAttribute(PDO::ATTR_ERRMODE,
			PDO::ERRMODE_EXCEPTION);

			// Protecting prepared statement against SQL injections
			$result = $dbconn->prepare("SELECT * FROM users WHERE username = :username");
			$result->execute(array('username' => $username));

			// We get the values of each row of the users table in the DB
			foreach($result as $row) {
				$adminStored = $row['admin'];
			}

			if($adminStored == 1)
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

		$username = filter_var($_SESSION['login_user'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_SPECIAL_CHARS);
		header("location: login.php");
	}
?>