<?php  
session_start();
require_once("pdo.php");

//* Permet le logout direct de la page
if (isset($_POST["cancel"])) {
  header("Location: ./logout.php");
  return;
}


if(isset($_POST['submit'])) {
      $name = htmlentities(trim($_POST['name']));
      $password = md5($_POST['password']);
 
 //*Permet de faire la vérification du compte dans la BDD  
  
if(!empty(trim($_POST['name'])) AND !empty(trim($_POST['password']))){
  // if(!empty($name) AND !empty($password)) {
      $requser = $pdo->prepare("SELECT * FROM users WHERE  name = ? AND password = ?");
      $requser->execute(array($name, $password,));
      $userexist = $requser->rowCount();
      if($userexist == 1) {
         $userinfo = $requser->fetch(PDO::FETCH_ASSOC);
         var_dump($userinfo);
         $_SESSION['user_id'] = $userinfo['user_id'];
         $_SESSION['name'] = $userinfo['name'];
         $_SESSION['password'] = $userinfo['password'];
         header("Location: app.php?user_id=".$_SESSION['user_id']);
      } else {
         $message = "Mauvais identifiant ou mot de passe !";
      }
   } else {
      $message = "Tous les champs doivent être complétés !";
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/normalize.css">
  <link rel="stylesheet" href="./css/styles.css">
</head>
<body>
     <section class="section">   
            <form action="" method="POST" class="form">
                 <?php
                 if (isset($message)) {
                    echo('<p style="color: red;">'.htmlentities($message)."</p>\n");
                }
            ?>
                <h4>Connectez-Vous</h4>
                    <div class="form-row">
                        <label for="name" class="form-label">Nom d'utilisateur</label>
                        <input type="text" name="name" id="name" class="form-input" >
                    </div>
                    <div class="form-row">
                        <label for="password" class="form-label">Mot de Passe</label>
                        <input type="password" name="password" id="password" class="form-input" >
                    </div>
                        <button type="submit" class="btn" name="submit">se connecter</button><br><br>
                        <button type="submit" name="cancel" class="btn">annuler</button>
    
            </form>
        </section>
</body>
</html>
