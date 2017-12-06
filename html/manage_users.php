<!DOCTYPE html>

<!--
	This is the interface that permits to an admin to manage the users registred in the database.
	An admin user can modify the current user's access rights, create new ones and delete them.
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

<!--ajouter/supprimer un user-->
<html>
	<head>
		<title>Manage users</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
		<header>
		<div id="header-div">
			<div id="page-title">
				<h3>Manage users</h3>
			</div>
			<div id="logout-div">
				<a href="logout.php" id="logout"> Logout</a>
			</div>
		</div>
	</header>

	<div id="main-div">
		<div id="left" class="bar"><a href="new_user.php" id="chpass-a">Create a new user</a></div>
		<div id="center">
				<a href="all_users.php" id="view-msg-a">Show registered users</a>
		</div>
	</div>
		<button onclick="history.go(-1);" class="back-btn">Back</button>
	</body>
</html>
