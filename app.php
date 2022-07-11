<?php
require_once("./pdo.php");
session_start();

//*permet de vérifier si l'utilisateur à bien ouvert son espace sinon accès refusé 

if (!isset($_SESSION["user_id"])) {
    die("Accès refusé");
}
//*Permet de faire la déconnexion totale du profil

if (isset($_POST["deconnexion"])) {
  header("Location: ./logout.php");
  return;
}

//*Permet de faire le vide complet des tâches 

 if(isset($_POST["videTotal"])) {
  $sql = "DELETE  FROM tasks WHERE user_id = :user_id";
                $stmt = $pdo->prepare($sql);
                 $stmt->execute([
                   ":user_id" => $_SESSION ["user_id"]
                 ]);
                
                $_SESSION["success"] = "Toutes les tâches sont supprimées";
                header("Location: app.php");
                return; 
           }
 


//*Liste des tâches de l'utilisateur 

$sql = "SELECT * FROM tasks WHERE user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ":user_id" => $_SESSION["user_id"]
]);


//*Ajouter une nouvelle tâche 
if (isset($_POST["ajouter"])) {
    if (!empty($_POST["task"])) {
        $sql = "INSERT INTO `tasks`(`title`, `user_id`) VALUES (:task,:user_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":task" => $_POST["task"],
            ":user_id" => $_SESSION["user_id"]
        ]);
        $_SESSION["success"] = "Nouvelle tâche ajoutée";
        header("Location: app.php");
        return;
    } else {
        //*si tâche est vide 
        $_SESSION["error"] = "Pas de tâche ajoutée le champs est vide";
        header("Location: app.php");
        return;
    }
}
//*Permet d'éditer une tâche 

if (isset($_POST["editer"])) {
    $_SESSION["task_id"] = $_POST["task_id"];
    header("Location: edit.php");
    return;
}

//*Permet de supprimer une tâche

if(isset($_POST["supprimer"])) {
  $sql = "DELETE FROM tasks WHERE task_id = :task_id";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ":task_id" => $_POST['task_id']
  ]);
  $_SESSION['success']= "Votre Tache est supprimé ! ";
  header("Location: app.php");
  return;        
}

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application || TO DO LIST</title>
    <link rel="stylesheet" href="./css/normalize.css">
    <link rel="stylesheet" href="./css/styles.css">
</head>

<body>
        <h3 class="title"><?php
    echo  "Bienvenue"; ?> <br>
    <?php echo   "$_SESSION[name]";?>
    voici vos Tâches personnelles </h3>
        <div class="form-row">
           <form action="" method="POST" class="form">
                              <?php
         //*permet d'afficher les messages "flash" sur la page APP
          
        if (isset($_SESSION["error"])) {
            echo "<big style='color: red'>{$_SESSION["error"]}</big>";
            unset($_SESSION["error"]);
        }
        if (isset($_SESSION["success"])) {
            echo "<big style='color: green'>{$_SESSION["success"]}</big>";
            unset($_SESSION["success"]);
        }
        ?>
               <br><input type="text" name="task" placeholder="Inscrire une nouvelle tâche">
                <button class="btn" type="submit" name="ajouter">Ajouter</button>
   </form>
                 
            <h4 class="title">Ma liste de Tâches</h4>
            <?php
        echo"<table border='1'>";
            foreach ($rows as $value) {
                $tab = <<<EOL
                <tr>
                
                <td>{$value["task_id"]}</td>
                <td>{$value["title"]}</td>

                <td>
                  <form method="POST">
                    <input type="hidden" name="task_id" value="{$value["task_id"]}">
                    <button class="btn" type="submit" name="supprimer">Supprimer</button>
                    <button class="btn" type="submit" name="editer">Editer</button>
                  </form>   
                </td>
                </tr>
                EOL;
                echo $tab;
            }
        echo"</table>";
           
            ?>

                        </div>
                        </div><br>
                        <form action="" method="POST">
                            <button type="submit" name="videTotal">Vider La Liste</button>
                             <button class="btn"name="deconnexion">Se Déconnecter</button>   
                        </form>
                        
                        </body>
                        </html>
