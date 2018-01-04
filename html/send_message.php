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
      // Disabling emulated prepared statements
      $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      // Set errormode to exceptions
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      // Get recipient's id with protection against SQL injections
      $result = $db->prepare("SELECT id FROM users WHERE username = :recipient");
      $result->execute(array('recipient' => $recipient));

      $recipient = $result->fetch();
      $id_recipient = $recipient['id'];

      // Get current user's id with protection against SQL injections
      $result = $db->prepare("SELECT id FROM users WHERE username = :username");
      $result->execute(array('username' => $username));

      $current_user = $result->fetch();
      $id_current_user = $current_user['id'];

      // Insert message into DB with protection against SQL injections
      $insert = $db->prepare("INSERT INTO messages(title, message, time, sender_id, receiver_id) 
	VALUES (:title, :message, datetime(), :sender_id, :receiver_id)");
      $insert->execute(array('title' => $title, 'message' => $message_content, 'sender_id' => $id_current_user, 'receiver_id' => $id_recipient));

      // Close file db connection
      $db = null;

      $_SESSION['message_sent'] = true;
      header("location: index.php");

    }
  }
  catch(PDOException $e) {
    // Print PDOException message
    echo $e->getMessage();
  }

?>
