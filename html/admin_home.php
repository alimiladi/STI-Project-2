<!DOCTYPE html>
<!--
	This page is the landing page for an admin user.
	Admins can create new users, display all registered ones, modify their access/validity and delete them.
	Admins also can deal with messages as regular users do.
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

<html>
	<head>
		<title>Admin home</title>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>

	<body>
		<header>
		<div id="header-div">
			<div id="page-title">
				<h3>Admin home</h3>
			</div>
			<div id="logout-div">
				<a href="logout.php" id="logout"> Logout</a>
			</div>
		</div>
	</header>


		<div id="main-div">
			<div id="left" class="bar"><a href="chpasswd.php" id="chpass-a">Change password</a></div>
			<div id="right" class="bar"><a href="manage_users.php" id="manage-users-a">Manage users</a></div>
			<div id="center">
					<a href="list_received_mails.php" id="view-msg-a">View Messages</a>
					<a href="mailbox.php" id="new-msg-a" class="bar">New message</a>
			</div>
		</div>

		<footer id="footer">
			Copyright (c) 2017 Copyright Holder All Rights Reserved.
		</footer>


	</body>

</html>
