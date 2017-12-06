<!--
		This scripts aims to write in the database the changes concerning the flags 'admin' and 'active'
		One of the several formulars in the 'all_users' page get's posted to this script and contains the information
		to be writte in the DB.
		This page is not mean to be accessed by anyone. This is the reason why we check that the id 'fetched_id' has
		been effectively posted.
-->

<?php
/**********************************************************************************************************************************************/
/*							Authentication checks								      */
/**********************************************************************************************************************************************/

	// Checking whether the user is logged in...
	session_start();
	if(!isset($_SESSION['login_user'])){
	// ...If not, redirect to the login page
		header("location: login.php");
	}
	else if (!isset($_GET['fetched_id']))
	{
		echo "<script type='text/javascript'>alert('Unauthorized');history.go(-1);</script>";
	}
?>

<html>
	<head></head>
	<body>
		<?php
			try{
				// Create (connect to) SQLite database in file
				$dbconn = new PDO('sqlite:/var/www/databases/database.sqlite');
				// Set errormode to exceptions
				$dbconn->setAttribute(PDO::ATTR_ERRMODE,
						    PDO::ERRMODE_EXCEPTION);

				//Checking for checkboxes validity
				if(isset($_POST['active'])){
					if(isset($_POST['admin'])){
						$dbconn->exec("UPDATE users SET active = '1', admin='1' WHERE id = '".$_GET['fetched_id']."';");
					}
					else{
						$dbconn->exec("UPDATE users SET active = '1', admin='0' WHERE id = '".$_GET['fetched_id']."';");
					}
				}
				else{
					if(isset($_POST['admin'])){
						$dbconn->exec("UPDATE users SET active = '0', admin='1' WHERE id = '".$_GET['fetched_id']."';");
					}
					else{
						$dbconn->exec("UPDATE users SET active = '0', admin='0' WHERE id = '".$_GET['fetched_id']."';");
					}
				}
				// Redirect to originating page
				header("location: all_users.php");

				// Close file db connection
	    			$dbconn = null;
			}
			catch(PDOException $e) {
				// Print PDOException message
				echo $e->getMessage();
			}
		?>
	</body>
</html>
