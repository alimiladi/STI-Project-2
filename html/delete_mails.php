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

    echo $_GET['message_id'];

    if(!empty($_GET['message_id']))
    {

      $message_id = $_GET['message_id'];

      // Create (connect to) SQLite database in file
      $db = new PDO('sqlite:/var/www/databases/database.sqlite');

      // Set errormode to exceptions
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      // Get id of current user
      $result = $db->query("SELECT id FROM users WHERE username = '$username'");
      $current_user = $result->fetch();
      $id_current_user = $current_user['id'];

      // Check if the user who want to delete the message is the recipient
      $result = $db->query("SELECT COUNT(*) as count FROM messages WHERE id = '$message_id' AND receiver_id = '$id_current_user'");
      $count = $result->fetchColumn();

      if($count == 1)
      {
        // Delete the message
        $result = $db->query("DELETE FROM messages WHERE id = '$message_id'");

        $_SESSION['message_deleted'] = true;
      }

      header("location: index.php");
    }
  }
  catch(PDOException $e) {
    // Print PDOException message
    echo $e->getMessage();
  }

?>
