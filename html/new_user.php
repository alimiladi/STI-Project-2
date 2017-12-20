<!--
	This page aims to add a new user to the database.
	It is actually not accesible neither for users that are not logged in nor for the ones that are not admins even if they are logged in.
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
	// In the case that he isn't an admin user, show a popup error and go back to the privious page.
		if (isset($_SESSION['admin'])){
			$username = $_SESSION['login_user'];
		}
	else{
			echo "<script type='text/javascript'>alert('Unauthorized');history.go(-1);</script>";
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Add a new user</title>
		<meta charset="utf-8">
		<link href="style.css" rel="stylesheet" type="text/css">
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function(){
					$(".create").click(function(){
						if ($("#form").data("changed")) {
							alert("User created successfully");
						}
					});
					$("#form :input").change(function() {
						$("#form").data("changed",true);
					});
			});
		</script>
	</head>
	<body>
		<header>
		<div id="header-div">
			<div id="page-title">
				<h3>Add a user</h3>
			</div>
			<div id="logout-div">
				<a href="logout.php" id="logout"> Logout</a>
			</div>
		</div>
	</header>

		<form action="new_user.php" method="post" class="form" id="new-user-form">
			<input type="text" name="Username" class="Username" id="usrname" placeholder="username" required> <br>
			<input type="password" name="Password" class="Password" id="passwd" placeholder="password" required><br>
			<label>Active</label>
			<input type="checkbox" name="active" value="active" class="active-check" checked><br/>
			<label>Admin</label>
			<input type="checkbox" name="admin" value="admin" class="admin-check"><br/><br/>
			<input type="submit" value="create" id="signin" name="create" class="create">
		</form>
	<?php
/**********************************************************************************************************************************************/
/*							DB Interactions*/
/**********************************************************************************************************************************************/
			try{
				// Create (connect to) SQLite database in file
				$dbconn = new PDO('sqlite:/var/www/databases/database.sqlite');
				// Set errormode to exceptions
				$dbconn->setAttribute(PDO::ATTR_ERRMODE,
						    PDO::ERRMODE_EXCEPTION);
				//Checking whether fields are correctly set by user
				if(isset($_POST['Username']) && isset($_POST['Password'])){

				//Password crypting with hash() function
				$_POST['Password']= hash('sha256', $_POST['Password']);

				//Checking for checkboxes validity
					if(isset($_POST['active'])){
						if(isset($_POST['admin'])){
							$dbconn->exec("INSERT INTO users (username, active, password, admin)
							VALUES ('{$_POST['Username']}', '1', '{$_POST['Password']}', '1')");
						}
						else{
							$dbconn->exec("INSERT INTO users (username, active, password, admin)
							VALUES ('{$_POST['Username']}', '1', '{$_POST['Password']}', '0')");
						}
					}
					else{
						if(isset($_POST['admin'])){
								$dbconn->exec("INSERT INTO users (username, active, password, admin)
								VALUES ('{$_POST['Username']}', '0', '{$_POST['Password']}', '1')");
							}
							else{
								$dbconn->exec("INSERT INTO users (username, active, password, admin)
								VALUES ('{$_POST['Username']}', '0', '{$_POST['Password']}', '0')");
							}
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
		<button onclick="history.go(-1);">Back</button>
	</body>
</html>
