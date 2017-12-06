
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">

  <title>Details</title>
<script src="/answer_mails.js"></script>
<link rel="stylesheet" type="text/css" href="style.css">

</head>
<body>
  <header>
  <div id="header-div">
    <div id="page-title">
      <h3>Details</h3>
    </div>
    <div id="logout-div">
      <a href="logout.php" id="logout"> Logout</a>
    </div>
  </div>
</header>
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

  try {

    if(!empty($_GET['message_id']))
    {
      $message_id = $_GET['message_id'];

      // Create (connect to) SQLite database in file
      $db = new PDO('sqlite:/var/www/databases/database.sqlite');

      // Set errormode to exceptions
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      // Get info about the message
      $result = $db->query("SELECT * FROM messages WHERE id = '$message_id'");
      $message = $result->fetch();

      // Get id of current user
      $result = $db->query("SELECT id FROM users WHERE username = '$username'");
      $current_user = $result->fetch();
      $id_current_user = $current_user['id'];

      // Check if the user who want to see the message is the recipient
      $result = $db->query("SELECT COUNT(*) as count FROM messages WHERE id = '$message_id' AND receiver_id = '$id_current_user'");
      $count = $result->fetchColumn();

      if($count == 1)
      {
        //retrieve sender identity
        $id_sender = $message['sender_id'];
        $result_sender = $db->query("SELECT username FROM users WHERE id = '$id_sender'");
        $sender = $result_sender->fetch();

        echo '<div class="container" align="center">';
        echo 'From : </br>'.$sender['username'];
        echo '</div>';

        echo '<div class="container" align="center">';
        echo 'Subject : </br>' .$message['title'];
        echo '</div>';

        echo '<div class="container" align="center">';
	      echo 'At :</br>'. $message['time'];
        echo '</div>';

        echo '<div class="container" align="center">';
        echo 'Message : </br>' .$message['message'];
        echo '</div>';
	        echo '
        <button onclick="answer_mails(&quot;'. $sender['username'] . '&quot;,&quot;' . $message['title'] . '&quot;)" id="right">Answer</button>
        <a class="btn-default" href="delete_mails.php?message_id='. $message['id'] . '" id="right">Delete</a>';
      }
      else
      {
        header("location: index.php");
      }
    }
  }
  catch(PDOException $e) {
    // Print PDOException message
    echo $e->getMessage();
  }

?>
<button onclick="history.go(-1);" class="back-btn">Back</button>

</body>
</html>
