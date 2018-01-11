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
			$username = filter_var($_SESSION['login_user'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_SPECIAL_CHARS);
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
							echo "<form class='form' method='post' action='admin_active.php?fetched_id=".filter_var($row['id'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_SPECIAL_CHARS)."'>";
							echo "<label>Username: " .filter_var($row['username'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_SPECIAL_CHARS)."</label><br/>";
							echo "<input type='password' id='Password' name='password' placeholder='".filter_var($row['password'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_SPECIAL_CHARS)."'><br/>";
							echo "<label>Active: </label>";
							echo "<input type='checkbox' name='active' value='active' checked><br/>";
							echo "<label>Admin: </label>";
							echo "<input type='checkbox' name='admin' value='admin' checked><br/>";
							echo "<input type='submit' name='".filter_var($row['id'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_SPECIAL_CHARS)."' value='save' class='submit'>";
							echo "</form>";
							echo "<button name='delete' onclick=\"self.location.href='del_user.php?id=".filter_var($row['id'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_SPECIAL_CHARS)."'\" class=\"logout\">delete</button>";
						}
						else{
							echo "<form class='form' method='post' action='admin_active.php?fetched_id=".filter_var($row['id'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_SPECIAL_CHARS)."'>";
							echo "<label>Username: " .filter_var($row['username'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_SPECIAL_CHARS)."</label><br/>";
							echo "<input type='password' id='Password' name='password' placeholder='".filter_var($row['password'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_SPECIAL_CHARS)."'><br/>";
							echo "<label>Active: </label>";
							echo "<input type='checkbox' name='active' value='active' checked><br/>";
							echo "<label>Admin: </label>";
							echo "<input type='checkbox' name='admin' value='admin'><br/>";
							echo "<input type='submit' name='".filter_var($row['id'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_SPECIAL_CHARS)."' value='save' class='submit'>";
							echo "</form>";
							echo "<button name='delete' onclick=\"self.location.href='del_user.php?id=".filter_var($row['id'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_SPECIAL_CHARS)."'\" class=\"logout\">delete</button>";
						}
					}
					else{
						if($row['admin'] == 1){
							echo "<form class='form' method='post' action='admin_active.php?fetched_id=".filter_var($row['id'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_SPECIAL_CHARS)."'>";
							echo "<label>Username: " .filter_var($row['username'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_SPECIAL_CHARS)."</label><br/>";
							echo "<input type='password' id='Password' name='password' placeholder='".filter_var($row['password'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_SPECIAL_CHARS)."'><br/>";
							echo "<label>Active: </label>";
							echo "<input type='checkbox' name='active' value='active'><br/>";
							echo "<label>Admin: </label>";
							echo "<input type='checkbox' name='admin' value='admin' checked><br/>";
							echo "<input type='submit' name='".filter_var($row['id'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_SPECIAL_CHARS)."' value='save' class='submit'>";
							echo "</form>";
							echo "<button name='delete' onclick=\"self.location.href='del_user.php?id=".filter_var($row['id'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_SPECIAL_CHARS)."'\" class=\"logout\">delete</button>";
						}
						else{
							echo "<form class='form' method='post' action='admin_active.php?fetched_id=".filter_var($row['id'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_SPECIAL_CHARS)."'>";
							echo "<label>Username: " .filter_var($row['username'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_SPECIAL_CHARS)."</label><br/>";
							echo "<input type='password' id='Password' name='password' placeholder='".filter_var($row['password'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_SPECIAL_CHARS)."'><br/>";
							echo "<label>Active: </label>";
							echo "<input type='checkbox' name='active' value='active'><br/>";
							echo "<label>Admin: </label>";
							echo "<input type='checkbox' name='admin' value='admin' ><br/>";
							echo "<input type='submit' name='".filter_var($row['id'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_SPECIAL_CHARS)."' value='save' class='submit'>";
							echo "</form>";
							echo "<button name='delete' onclick=\"self.location.href='del_user.php?id=".filter_var($row['id'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_SPECIAL_CHARS)."'\" class=\"logout\">delete</button>";
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