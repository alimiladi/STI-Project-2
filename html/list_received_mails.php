<!DOCTYPE html>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Mailbox</title>
    <script src="/answer_mails.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
  </head>

  <body>
    <header>
      <div id="header-div">
        <div id="page-title">
          <h2>Received messages<h2/>
        </div>
        <div id="logout-div">
          <a href="logout.php" id="logout"> Logout</a>
        </div>
      </div>
    </header>
    <table cellpadding="10px" cellspacing="30%">
      <tr>
        <th>Sender</th>
        <th>Title</th>
        <th>Time</th>
      </tr>
    </table>

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


    // Create (connect to) SQLite database in file
    $db = new PDO('sqlite:/var/www/databases/database.sqlite');

    // Set errormode to exceptions
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //get user id
    $result = $db->query("SELECT id FROM users WHERE username = '$username';");
    $user = $result->fetch();
    $id_user = $user['id'];

    // retrieve the received messages
    $result = $db->query("SELECT * FROM messages WHERE receiver_id = '$id_user' ORDER BY time");

    while($message = $result->fetch())
    {
    //retrieve sender identity
    $id_sender = $message['sender_id'];
    $result_sender = $db->query("SELECT username FROM users WHERE id = '$id_sender'");
    $sender = $result_sender->fetch();

    // display messages in the table
    echo '<table cellpadding="10px" cellspacing="40px">
      <tr>
        <td>' . $sender['username'] . '</td>
        <td>' . $message['title'] . '</td>
        <td>' . $message['time'] . '</td>
      <td>
        <button class="btn btn-primary" onclick="answer_mails(&quot;'. $sender['username'] . '&quot;,&quot;' . $message['title'] . '&quot;)">Answer</button>
      </td>
      <td>
        <a class ="btn-default" href="detail_mails.php?message_id='. $message['id'] . '">Details</a>
      </td>
      <td>
        <a class = "btn-default" href="delete_mails.php?message_id='. $message['id'] . '">Delete</a> <br/>
      </td>
      </tr>
      </tr></table>
    <tr>
        ';
    }

    ?>
    <button onclick="history.go(-1);" class="back-btn">Back</button>
  </body>
</html>
