<!--
    This is the mail login page.
    It presents a form to the user in order to login.
    The form requires that all the fields has been filled in otherwise it doesn't post data.
    This page redirects the user to it's home page depending on his type.
    This is done by querying the DB about with the user's credentials, and figuring out if
    his 'admin' flag is set or not. It checks also that his 'active' flag is set, otherwise
    it denis the access displaying an error message.
-->
<?php
/**********************************************************************************************************************************************/
/*							Authentication checks								      */
/**********************************************************************************************************************************************/
  session_start();

  // Redirect to admin home if already connected and admin flag set to 1
  if(isset($_SESSION['login_user']) && isset($_SESSION['admin'])){
    header("location: admin_home.php");
  }
  // Redirect to regular user home if connected and admin flag unset
  else if(isset($_SESSION['login_user']) && !isset($_SESSION['admin'])){
    header("location: user_home.php");
  }

  // If password was wrong show warning message
  if(isset($_SESSION['wrong_password']) && $_SESSION['wrong_password']){
   	echo '<script type="text/javascript">
		alert("Login failed\nThe username/password doesn\'t match");
	</script>';

	// Unset wrong_password flag
    $_SESSION['wrong_password'] = false;
  }
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title>Login page</title>
    <link rel="stylesheet" type="text/css" href="style.css">
  </head>
  <body>
    <div class="login-page">
  <div class="form">
      <form class="login-form" action="process_login.php" method="post" role="form">
      <input type="text" placeholder="username" name="username" id="username" required/>
      <input type="password" placeholder="password" name="password" id="password" required/>
      <input type="submit" name="login-submit" id="login-submit" value="log in">
    </form>
  </div>
</div>
  <body>
</html>
