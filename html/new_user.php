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

		<form action="<?php echo htmlspecialchars('new_user.php');?>" method="post" class="form" id="new-user-form">
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
				// Disabling emulated prepared statements
				$dbconn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				// Set errormode to exceptions
				$dbconn->setAttribute(PDO::ATTR_ERRMODE,
						    PDO::ERRMODE_EXCEPTION);

				// Checking whether fields are correctly set by user
				if(isset($_POST['Username']) && isset($_POST['Password'])){
					if (!empty($_POST['Username']) && !empty($_POST['Password'])) {
						// Password crypting with hash() function
						$password = hash('sha256', $_POST['Password']);

						$username = filter_var($_POST['Username'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_SPECIAL_CHARS);

						// Checking for checkboxes validity with prepared statements to protect us against SQL injections
						if(isset($_POST['active'])) {
							if(isset($_POST['admin'])) {
								$insert = $dbconn->prepare("INSERT INTO users (username, active, password, admin)
								VALUES (:username, '1', :password, '1')");
								$insert->execute(array('username' => $username, 'password' => $password));
							}
							else{
								$insert = $dbconn->prepare("INSERT INTO users (username, active, password, admin)
								VALUES (:username, '1', :password, '0')");
								$insert->execute(array('username' => $username, 'password' => $password));
							}
						}
						else{
							if(isset($_POST['admin'])) {
									$insert = $dbconn->prepare("INSERT INTO users (username, active, password, admin)
									VALUES (:username, '0', :password, '1')");
									$insert->execute(array('username' => $username, 'password' => $password));
								}
								else{
									$insert = $dbconn->prepare("INSERT INTO users (username, active, password, admin)
									VALUES (:username, '0', :password, '0')");
									$insert->execute(array('username' => $username, 'password' => $password));
								}
						}
					}
					else {
						echo "<script>alert('Error ! Please fill all the required fields !');</script>";
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
