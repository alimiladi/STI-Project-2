<!DOCTYPE html>

<!--
	This is the landing page for a regular user of the app.
	A regular user has it's 'admin' flag turned off in the database.
	The features proposed to this kind of users are about handling messages.
	They can display all received messages in a kind of mailbox, shos any message details, answer a message and delete it.
	They don't have access to any of the admin pages.
-->

<?php
/**********************************************************************************************************************************************/
/*							Authentication checks								      */
/**********************************************************************************************************************************************/

	session_start();
	// Checking whether the user is logged in...
	if(!isset($_SESSION['login_user'])){
	// ...If not, redirect to the login page
		header("location: login.php");
	}
	else{
	// In the case that he is an admin user, this mean that he isn't allowed to access this page.
	// A popup error message is shown and he gets redirected back to the previous page.
		if (!isset($_SESSION['admin'])){
			$username = filter_var($_SESSION['login_user'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_SPECIAL_CHARS);
		}
		else{
			echo "<script type='text/javascript'>alert('Unauthorized');history.go(-1);</script>";
		}
	}
?>
<html>
	<head>
		<title>Regular user home</title>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>

			<header>
			<div id="header-div">
				<div id="page-title">
					<h3>User home</h3>
				</div>
				<div id="logout-div">
					<a href="logout.php" id="logout"> Logout</a>
				</div>
			</div>
		</header>

		<div id="main-div">
			<div id="left" class="bar"><a href="chpasswd.php" id="chpass-a">Change password</a></div>
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
