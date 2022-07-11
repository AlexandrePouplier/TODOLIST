<?php
//** ouverture de session et appel du PDO 
 session_start();
 require_once("./pdo.php");

//* Permet de faire la déconnexion directe depuis le bouton annuler 
if (isset($_POST["deconnexion"])) {
  header("Location: ./logout.php");
  return;
}

//*création de la condition du register pour confirmer l'inscription de l'utilisateur depuis le bouton s'enregistrer 

if(isset($_POST['enregistrement'])) 
{
    $name = htmlentities(trim($_POST['name']));
    $password = htmlentities(trim($_POST['password']));
    $password2 = htmlentities(trim($_POST['password2']));
     $name = strtolower($name);//*Permet de vérifier si le l'utilisateur et inscrit en majuscules et en minuscules 

if($name && $password && $password2)
{
   //*vérifie les MDP
if($password == $password2) 
{
     $password = md5($password);
    //*vérifie si l'utilisateur existe déja dans la BDD 
    $sql = "SELECT * FROM users WHERE name= :name";

   $stmt = $pdo->prepare($sql);

   $stmt->execute([
     ":name" => $name,
   ]);
$rows =$stmt->fetch(PDO::FETCH_ASSOC);
$_SESSION=$rows;
  if(!$rows){

  
  
   //* permet d'insérer les nouveaux utilisateurs dans la BDD 
     $sql = "INSERT INTO users (name, password) VALUES(:name,:password)";

            //echo ("<pre>" . $sql . "</pre>");

             $stmt = $pdo->prepare($sql);

            $stmt->execute([
        ":name" => $name,
        ":password" => $password
  ]);
 $message= "Votre compte a bien été créé avec succès !";
}else 
$message=  "l'utilisateur existe déja merci de trouver un autre identifiant";
}else 
 $message= "les deux mots de pas doivent etre identiques";
}else 
 $message= " Veulliez saisir tous les champs";

}
// session_start();

// require_once("pdo.php");

// if (isset($_POST["deconnexion"])) {
//   header("Location: ./logout.php");
//   return;
// }

// //  si le nom d'utilisateur existe déjà



// // if($name == $_POST['name']) {
//  $sql = "SELECT INTO users (title,user_id) Where (:user)";


// //  $stmt->execute([
// //     ":title" => $_POST["task"],
// //     ":user" => $_SESSION['user_id'],
// //   ]);




// if(isset($_POST['enregistrement'])) {

//    $name = htmlspecialchars($_POST['name']);
//    $password = sha1($_POST['password']);
//    $password2 = sha1($_POST['password2']);
//    if(!empty(trim($_POST['name'])) AND !empty(trim($_POST['password'])) AND !empty(trim($_POST['password2']))){
//       $namelength = strlen($name);
//       if($namelength <= 255) {
//         if($password == $password2) {
//                     $insertmbr = $pdo->prepare("INSERT INTO users (name, password) VALUES(?, ?)");
//                     $insertmbr->execute(array($name, $password));
//                     $message= "Votre compte a bien été créé avec succès !";
//                   } else {
//                     $message= "Les mots de passes ne sont pas identiques !";
//                   }   
                  
//       } else {
//         $message= "Votre nom ne doit pas dépasser 255 caractères !";
//       }
//    } else {
//       $message= "Tous les champs doivent être complétés !";
//    }
// }

// https://www.youtube.com/watch?v=60_jCo4p3Ds


// https://www.youtube.com/watch?v=zYMbjUmy2gU

// https://www.youtube.com/watch?v=fcZ9hL9xl6o 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S'enregistrer</title>
    <link rel="stylesheet" href="./css/normalize.css">
    <link rel="stylesheet" href="./css/styles.css">
</head>
<body>
    <section class="section">
        <form action="register.php" method="POST" class="form">
          <h4>Enregistrez-Vous</h4>
      
          <div class="form-row">
            <label for="name" class="form-label">Nom d'utilisateur</label>
            <input type="text" name="name" id="name" class="form-input">
          </div>
          <div class="form-row">
            <label for="pass" class="form-label">Mot de Passe</label>
            <input type="password" name="password" id="pass" class="form-input">
          </div>
          <div class="form-row">
            <label for="password2" class="form-label"> Confirmer Le Mot de Passe</label>
            <input type="password" name="password2" id="password2" class="form-input">
          </div>
            <button type="submit" class="btn" name="enregistrement">S'enregistrer</button><br><br>
        <?php
                if (isset($message)) {
                    echo('<p style="color: red;">'.htmlentities($message)."</p>\n");
                }
            ?> 
            <button class="btn" name="deconnexion">Annuler</button>
        </form>
    </section>

</body>
</html>