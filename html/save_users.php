<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
  <title>Messagerie</title>
  <link rel="stylesheet" href="style.css">
  <script src="script.js"></script>
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
	<form method="post" action="example.php">
   <fieldset>
       <legend>Vos coordonnées</legend> <!-- Titre du fieldset --> 

       <label for="nom">nom :</label>
       <input type="text" name="nom" id="nom" autofocus required /><br />

       <label for="prenom">prénom :</label>
       <input type="text" name="prenom" id="prenom" required />
 <br />
       <label for="email">e-mail :</label>
       <input type="email" name="email" id="email" required />
<br />
       <label for="pass">mot de passe :</label>
       <input type="email" name="pass" id="pass" required />
<br />
       <label for="pass">confirmation mot de passe :</label>
       <input type="password" name="pass" id="pass" required />

   </fieldset>


	 <input type="submit" value="Envoyer" />
</form>
	<button onclick="self.location.href='logout.php'">Disconnect</button>
</body>
</html>
