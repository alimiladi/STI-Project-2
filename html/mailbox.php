<!DOCTYPE html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Mailbox</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>


                   <?php

                      session_start();

                      if(!isset($_SESSION['login_user']))
                      {
                        header("location: login.php");
                      }
                      else
                      {
                        $username = filter_var($_SESSION['login_user'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_SPECIAL_CHARS);
                      }

?>
<header>
<div id="header-div">
  <div id="page-title">
    <h3> Write a new message </h3>
  </div>
  <div id="logout-div">
    <a href="logout.php" id="logout"> Logout</a>
  </div>
</div>
</header>
          <form id="message_form" action="<?php echo htmlspecialchars('send_message.php');?>" method="post" role="form">
            <div class="form-group">
             <label for="sel1">Recipient</label>
             <select class="form-control" name="recipient">

             <?php
                      // Create (connect to) SQLite database in file
                      $db = new PDO('sqlite:/var/www/databases/database.sqlite');
		      // Disabling emulated prepared statements
		      $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                      // Set errormode to exceptions
                      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // We get all users except us (we don't want to send a mail to ourselves)
	    // We use a prepared statement to protect us against SQL injections
	    $result = $db->prepare("SELECT * FROM users WHERE NOT username = :username");
	    $result->execute(array('username' => $username));
              while($row = $result->fetch())
             {
                echo '<option>'. $row['username'] .'</option>';
             }

	    // Close file db connection
            $db = null;
             ?>

              </select>
            </div>
            <div class="center" align="center">
            <div class="form-group">
                 <input type="text" class="form-control" name="title" placeholder="Title" required>
             </div>
             <div class="form-group">
                 <textarea id="message" name="message" placeholder="Content ..." required></textarea>
             </div>
             <div class="form-group">
             <input type="submit" class="btn btn-default" value="Send">
             </div>
           </div>
          </form>
          <?php
            if (isset($_SESSION['admin'])) {
              echo "<button onclick='document.location.href=\"admin_home.php\";' class='back-btn'>Back</button>";
            }
            else {
              echo "<button onclick='document.location.href=\"user_home.php\";' class='back-btn'>Back</button>";
            } 
           ?>
    </div>
  </div>

</body>
</html>
