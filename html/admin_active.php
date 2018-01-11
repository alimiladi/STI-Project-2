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
				$id = filter_var($_GET['fetched_id'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_SPECIAL_CHARS);				
							
				// Create (connect to) SQLite database in file
				$dbconn = new PDO('sqlite:/var/www/databases/database.sqlite');
				// Disabling emulated prepared statements
				$dbconn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				// Set errormode to exceptions
				$dbconn->setAttribute(PDO::ATTR_ERRMODE,
						    PDO::ERRMODE_EXCEPTION);

				if (isset($_POST['password'])) {
					if (!empty($_POST['password'])) {
						// Forcing a strong password policy
						$uppercase = preg_match('/[A-Z]/', $_POST['password']);
						$lowercase = preg_match('/[a-z]/', $_POST['password']);
						$number    = preg_match('/[0-9]/', $_POST['password']);

						if($uppercase == 1 && $lowercase == 1 && $number == 1 && strlen($_POST['password']) > 8) {

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
							// Crypting the password with the hash() function
							$password = hash('sha256', filter_var($_POST['password'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_SPECIAL_CHARS));		
							
							// Protecting against SQL injections with a prepared statement
							$update = $dbconn->prepare("UPDATE users SET password = :password WHERE username = :username");
							$update->execute(array('password' => $password, 'username' => $username));
							
							echo "<script>alert('Changes saved successfully');location='all_users.php';</script>";
							
						}
						else {
							echo "<script>alert(\"Your password must contain at least:\\n- 1 upper case letter\\n- 1 lower case letter\\n- 1 number\\n- 8 charcters length!\");location='all_users.php';</script>";
						}
					}
					else {
						echo "<script type='text/javascript'>alert('Forbidden \\nEmpty password!');location='all_users.php'</script>";
					}
				}
				
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
