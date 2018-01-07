
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
    $username = filter_var($_SESSION['login_user'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_SPECIAL_CHARS);
  }

  try {

    if(!empty($_GET['message_id']))
    {
      $message_id = filter_var($_GET['message_id'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_SPECIAL_CHARS);

      // Create (connect to) SQLite database in file
      $db = new PDO('sqlite:/var/www/databases/database.sqlite');
      // Disabling emulated prepared statements
      $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      // Set errormode to exceptions
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      // Get info about the message with protection against SQL injections
      $result = $db->prepare("SELECT * FROM messages WHERE id = :message_id");
      $result->execute(array('message_id' => $message_id));
      $message = $result->fetch();

      // Get id of current user
      $result = $db->prepare("SELECT id FROM users WHERE username = :username");
      $result->execute(array('username' => $username));
      $current_user = $result->fetch();
      $id_current_user = $current_user['id'];

      // Check if the user who want to see the message is the recipient
      $result = $db->prepare("SELECT COUNT(*) as count FROM messages WHERE id = :message_id AND receiver_id = :id_current_user");
      $result->execute(array('message_id' => $message_id, 'id_current_user' => $id_current_user));
      $count = $result->fetchColumn();

      if($count == 1)
      {
        //retrieve sender identity
        $id_sender = $message['sender_id'];
        $result_sender = $db->prepare("SELECT username FROM users WHERE id = :id_sender");
	$result_sender->execute(array('id_sender' => $id_sender));
        $sender = $result_sender->fetch();

	// Close file db connection
	$db = null;

        echo '<div class="container" align="center">';
        echo 'From : </br>'.filter_var($sender['username'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_SPECIAL_CHARS);
        echo '</div>';

        echo '<div class="container" align="center">';
        echo 'Subject : </br>' .filter_var($message['title'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_SPECIAL_CHARS);
        echo '</div>';

        echo '<div class="container" align="center">';
	      echo 'At :</br>'. filter_var($message['time'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_SPECIAL_CHARS);
        echo '</div>';

        echo '<div class="container" align="center">';
        echo 'Message : </br>' .filter_var($message['message'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_SPECIAL_CHARS);
        echo '</div>';
	        echo '
        <button onclick="answer_mails(&quot;'. $sender['username'] . '&quot;,&quot;' . $message['title'] . '&quot;)" id="right">Answer</button>
        <a class="btn-default" href="delete_mails.php?message_id='. $message['id'] . '" id="right">Delete</a>';
      }
      else
      {
        // Close file db connection
	$db = null;
	
	header("location: index.php");
      }
    }
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
