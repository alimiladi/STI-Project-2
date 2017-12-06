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
    if(!empty($_POST["recipient"]) && !empty($_POST["title"]) && !empty($_POST["message"]))
    {

      $recipient = $_POST["recipient"];
      $title = $_POST["title"];
      $message_content = $_POST["message"];

      // Create (connect to) SQLite database in file
      $db = new PDO('sqlite:/var/www/databases/database.sqlite');

      // Set errormode to exceptions
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      // Get recipient's id
      $result = $db->query("SELECT id FROM users WHERE username = '$recipient'");
      $recipient = $result->fetch();
      $id_recipient = $recipient['id'];

      // Get current user's id
      $result = $db->query("SELECT id FROM users WHERE username = '$username'");
      $current_user = $result->fetch();
      $id_current_user = $current_user['id'];

      $db->query("INSERT INTO messages(title, message, time, sender_id, receiver_id) VALUES ('". $title ."','". $message_content ."', datetime(),'" . $id_current_user . "','" . $id_recipient . "' )");

      $_SESSION['message_sent'] = true;
      header("location: index.php");

    }
  }
  catch(PDOException $e) {
    // Print PDOException message
    echo $e->getMessage();
  }

?>
