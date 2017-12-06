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
                        $username = $_SESSION['login_user'];
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
          <form id="message_form" action="send_message.php" method="post" role="form">
            <div class="form-group">
             <label for="sel1">Recipient</label>
             <select class="form-control" name="recipient">

             <?php
                      // Create (connect to) SQLite database in file
                      $db = new PDO('sqlite:/var/www/databases/database.sqlite');

                      // Set errormode to exceptions
                      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $result = $db->query("SELECT * FROM users WHERE NOT username = '$username'");
              while($row = $result->fetch())
             {
                echo '<option>'. $row['username'] .'</option>';
             }
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
          <button onclick="history.go(-1);" class="back-btn">Back</button>
    </div>
  </div>

</body>
</html>
