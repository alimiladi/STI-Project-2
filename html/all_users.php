<!--
		This is the main page thas displays all the details of all the registred users in the database.
		This page is not meant to be accessed by a regular user, but only by admins.
		This is the reason why a popup error message gets displayed and a redirection to the previous page happens
		when they try to access this page.
		An admin user can display all the usernames. He can display also their 'admin' and 'active' flags and modify them.
		The modification gets effective only if the user hits the button 'submit' of the desired form. In fact, a popup message
		shows that the changes has effectively been saved in the database.
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
	// In the case that he isn't an admin user, show a popup error and go back to the previous page.
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
		<title>Registred users</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style.css">
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function(){
				$(".submit").click(function(){
					alert("Changes saved successfully");
				});
			});
		</script>
	</head>

	<body>
		<header>
		<div id="header-div">
			<div id="page-title">
				<h3>All registred users</h3>
			</div>
			<div id="logout-div">
				<a href="logout.php" id="logout"> Logout</a>
			</div>
		</div>
	</header>

		<?php
			try{
				// Create (connect to) SQLite database in file
				$dbconn = new PDO('sqlite:/var/www/databases/database.sqlite');
				// Disabling emulated prepared statements
				$dbconn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				// Set errormode to exceptions
				$dbconn->setAttribute(PDO::ATTR_ERRMODE,
				PDO::ERRMODE_EXCEPTION);

				// Fetching all the users from the database (with protection against SQL injection)
				$users = $dbconn->prepare("SELECT * FROM users");
				$users->execute();

				// This section aims to display a different form for each registered user in the DB.
				// This is done by iterating over the users and check the content of the 'admin' and 'active' flags.
				foreach($users as $row) {
					echo "<div class='all-users'>";
					if($row['active'] == 1){
						if($row['admin'] == 1){
							echo "<form class='form' method='post' action='admin_active.php?fetched_id=".$row['id']."'>";
							echo "<label>Username: " .$row['username']."</label><br/>";
							echo "<label>Active: </label>";
							echo "<input type='checkbox' name='active' value='active' checked><br/>";
							echo "<label>Admin: </label>";
							echo "<input type='checkbox' name='admin' value='admin' checked><br/>";
							echo "<input type='submit' name='".$row['id']."' value='save' class='submit'>";
							echo "</form>";
							echo "<button name='delete' onclick=\"self.location.href='del_user.php?id=".$row['id']."'\" class=\"logout\">delete</button>";
						}
						else{
							echo "<form class='form' method='post' action='admin_active.php?fetched_id=".$row['id']."'>";
							echo "<label>Username: " .$row['username']."</label><br/>";
							echo "<label>Active: </label>";
							echo "<input type='checkbox' name='active' value='active' checked><br/>";
							echo "<label>Admin: </label>";
							echo "<input type='checkbox' name='admin' value='admin'><br/>";
							echo "<input type='submit' name='".$row['id']."' value='save' class='submit'>";
							echo "</form>";
							echo "<button name='delete' onclick=\"self.location.href='del_user.php?id=".$row['id']."'\" class=\"logout\">delete</button>";
						}
					}
					else{
						if($row['admin'] == 1){
							echo "<form class='form' method='post' action='admin_active.php?fetched_id=".$row['id']."'>";
							echo "<label>Username: " .$row['username']."</label><br/>";
							echo "<label>Active: </label>";
							echo "<input type='checkbox' name='active' value='active'><br/>";
							echo "<label>Admin: </label>";
							echo "<input type='checkbox' name='admin' value='admin' checked><br/>";
							echo "<input type='submit' name='".$row['id']."' value='save' class='submit'>";
							echo "</form>";
							echo "<button name='delete' onclick=\"self.location.href='del_user.php?id=".$row['id']."'\" class=\"logout\">delete</button>";
						}
						else{
							echo "<form class='form' method='post' action='admin_active.php?fetched_id=".$row['id']."'>";
							echo "<label>Username: " .$row['username']."</label><br/>";
							echo "<label>Active: </label>";
							echo "<input type='checkbox' name='active' value='active'><br/>";
							echo "<label>Admin: </label>";
							echo "<input type='checkbox' name='admin' value='admin' ><br/>";
							echo "<input type='submit' name='".$row['id']."' value='save' class='submit'>";
							echo "</form>";
							echo "<button name='delete' onclick=\"self.location.href='del_user.php?id=".$row['id']."'\" class=\"logout\">delete</button>";
						}
					}
					echo "</div>";
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
