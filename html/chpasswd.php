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
		$username = $_SESSION['login_user'];
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style.css">
		<title>Change password</title>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function(){
			    $(".submit").click(function(){
						if ($("#chpass-form").data("changed")) {
							alert("Password changed successfully");
						}
			    });
					$("#chpass-form :input").change(function() {
   					$("#chpass-form").data("changed",true);
					});
			});
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
		<form action="chpasswd.php" method="post" id="chpass-form">
			<label>New password :</label>
			<input type="password" name="Password" class="Password" id="Password" placeholder="Type your new password here" required> <br>
			<input type="submit" name="submit" value="submit" class="submit">
		</form>
	</div>
		<?php
			try{
				// Create (connect to) SQLite database in file
				$dbconn = new PDO('sqlite:/var/www/databases/database.sqlite');
				// Set errormode to exceptions
				$dbconn->setAttribute(PDO::ATTR_ERRMODE,
						    PDO::ERRMODE_EXCEPTION);
				//Checking whether fields are correctly set by user
				if(isset($_POST['Password'])){
					$dbconn->exec("UPDATE users SET password = '{$_POST['Password']}' WHERE username = '$username';");
				}
				// Close file db connection
	    			$dbconn = null;
			}
			catch(PDOException $e) {
				// Print PDOException message
				echo $e->getMessage();
			}
		?>
		<button onclick="history.go(-1);" class="back-btn">Back</button>
	</body>
</html>
