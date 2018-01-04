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
				// We get the id of the user we want to modify				
				$id = $_GET['fetched_id'];				
							
				// Create (connect to) SQLite database in file
				$dbconn = new PDO('sqlite:/var/www/databases/database.sqlite');
				// Disabling emulated prepared statements
				$dbconn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				// Set errormode to exceptions
				$dbconn->setAttribute(PDO::ATTR_ERRMODE,
						    PDO::ERRMODE_EXCEPTION);

				//Checking for checkboxes validity with protection against SQL injections
				if(isset($_POST['active'])){
					if(isset($_POST['admin'])){
						$update = $dbconn->prepare("UPDATE users SET active = '1', admin='1' WHERE id = :id");
						$update->execute(array('id' => $id));
					}
					else{
						$update = $dbconn->prepare("UPDATE users SET active = '1', admin='0' WHERE id = :id");
						$update->execute(array('id' => $id));
					}
				}
				else{
					if(isset($_POST['admin'])){
						$update = $dbconn->prepare("UPDATE users SET active = '0', admin='1' WHERE id = :id");
						$update->execute(array('id' => $id));
					}
					else{
						$update = $dbconn->prepare("UPDATE users SET active = '0', admin='0' WHERE id = :id");
						$update->execute(array('id' => $id));
					}
				}

				// Close file db connection
	    			$dbconn = null;

				// Redirect to originating page
				header("location: all_users.php");		
			}
			catch(PDOException $e) {
				// Print PDOException message
				echo $e->getMessage();
			}
		?>
	</body>
</html>
