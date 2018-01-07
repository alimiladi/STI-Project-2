<!--
		This is the page that changes a user's password.
		There isn't any checks about the new password unless it can't be empty.
		The new password is written directly in the database and the user gets notified by the end of the operation.
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
	else{
		$username = filter_var($_SESSION['login_user'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_SPECIAL_CHARS);
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style.css">
		<title>Change password</title>
		<script>
        function validate(){
            var p = document.getElementById("Password").value;
            var pc = document.getElementById("PasswordConfirm").value;
            if (p!=pc) {
               alert("Passwords do no match !");
               return false;
            }
        }
     </script>
	</head>
	<body>
		<header>
		<div id="header-div">
			<div id="page-title">
				<h3>Change your password</h3>
			</div>
			<div id="logout-div">
				<a href="logout.php" id="logout"> Logout</a>
			</div>
		</div>
	</header>
	<div class="center" align="center">
		<form onSubmit="return validate();" action="<?php echo htmlspecialchars('chpasswd.php');?>" method="post" id="chpass-form">
			<input type="password" name="Password" class="Password" id="Password" placeholder="Type your new password here" required> <br>
			<input type="password" name="PasswordConfirm" class="Password" id="PasswordConfirm" placeholder="Confirm password" required> <br>
			<input type="submit" name="submit" value="submit" class="submit">
		</form>
	</div>
		<?php
			try{
				// Create (connect to) SQLite database in file
				$dbconn = new PDO('sqlite:/var/www/databases/database.sqlite');
				// Disabling emulated prepared statements
				$dbconn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				// Set errormode to exceptions
				$dbconn->setAttribute(PDO::ATTR_ERRMODE,
						    PDO::ERRMODE_EXCEPTION);
				//Checking whether fields are correctly set by user
				if(isset($_POST['Password'])){
					if (!empty($_POST['Password'])) {
						if ($_POST['Password'] == $_POST['PasswordConfirm']) {

							// Forcing a strong password policy
							$uppercase = preg_match('/[A-Z]/', $_POST['Password']);
							$lowercase = preg_match('/[a-z]/', $_POST['Password']);
							$number    = preg_match('/[0-9]/', $_POST['Password']);
							$regexp = '#^.*(?=.{8,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^0-9a-zA-Z]).*$#';

							if($uppercase == 1 && $lowercase == 1 && $number == 1 && strlen($_POST['Password']) > 8) {
								// Crypting the password with the hash() function
								$password = hash('sha256', filter_var($_POST['Password'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_SPECIAL_CHARS));		
								
								// Protecting against SQL injections with a prepared statement
								$update = $dbconn->prepare("UPDATE users SET password = :password WHERE username = :username");
								$update->execute(array('password' => $password, 'username' => $username));
								
								echo "<script>alert('Password changed successfully !');</script>";
								
								if (isset($_SESSION['admin'])) {
									echo "<script>location='admin_home.php';</script>";
								}
								else {
									echo "<script>location='user_home.php.php';</script>";
								}
							}
							else {
								echo "<script>alert(\"Your password must contain at least:\\n- 1 upper case letter\\n- 1 lower case letter\\n- 1 number\\n- 8 charcters length!\");</script>";
							}
						}
					}
					else{
						echo "<script type='text/javascript'>alert('Forbidden \\nEmpty password!');</script>";
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
		<?php
            if (isset($_SESSION['admin'])) {
              echo "<button onclick='document.location.href=\"admin_home.php\";' class='back-btn'>Back</button>";
            }
            else {
              echo "<button onclick='document.location.href=\"user_home.php\";' class='back-btn'>Back</button>";
            } 
        ?>
	</body>
</html>
