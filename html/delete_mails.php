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

      // Get id of current user with protection against SQL injection
      $result = $db->prepare("SELECT id FROM users WHERE username = :username");
      $result->execute(array('username' => $username));
      $current_user = $result->fetch();
      $id_current_user = $current_user['id'];

      // Check if the user who want to delete the message is the recipient
      $result = $db->prepare("SELECT COUNT(*) as count FROM messages WHERE id = :message_id AND receiver_id = :id_current_user");
      $result->execute(array('message_id' => $message_id, 'id_current_user' => $id_current_user));
      $count = $result->fetchColumn();

      if($count == 1)
      {
        // Delete the message
        $result = $db->prepare("DELETE FROM messages WHERE id = :message_id");
	$result->execute(array('message_id' => $message_id));

        $_SESSION['message_deleted'] = true;
      }

      // Close file db connection
      $db = null;

      header("location: index.php");
    }
  }
  catch(PDOException $e) {
    // Print PDOException message
    echo $e->getMessage();
  }

?>
