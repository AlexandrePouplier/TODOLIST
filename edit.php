<?php

session_start();
require_once("pdo.php");


//*permet de vérifier si l'utilisateur à bien ouvert son espace sinon accès refusé 

if (!isset($_SESSION["user_id"])) {
    die("Accès refusé");
}

//*Permet de faire la déconnexion totale du profil

if (isset($_POST["cancel"])) {
  header("Location: ./app.php");
  return;
}

//* Permet de modifier la tâche  

if (isset($_POST["modiftache"])) {
  if (!empty(trim($_POST["task"]))) {
$sql = "UPDATE tasks SET title = :title WHERE task_id = :task_id";
$task = htmlentities($_POST["task"]);
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
            ":title" => $task,
            ":task_id" => ($_SESSION['task_id'])
  ]);
 $_SESSION["success"] = "Votre tâche à bien été modifiée !";
 header("Location: app.php");
        return;
  }else  {
$_SESSION["error"] = "Les champs sont vide ! ";
header("Location: edit.php?todos_id=".$_REQUEST['id']);
return;
  }
}

//*Permet d'éditer la tache selectionnée sur la page APP

$sql = "SELECT * FROM tasks WHERE task_id = :task_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ":task_id" => $_SESSION["task_id"]
]);

$value = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Éditer</title>
    <link rel="stylesheet" href="./css/normalize.css">
  <link rel="stylesheet" href="./css/styles.css">
</head>
<body>
     <section class="section">   
            <form action="" method="POST" class="form">
                <h4>Modifier Une Tâche</h4>
                    <div class="form-row">
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
                        <label for="task" class="form-label">Modification de la Tâche</label>
                                <input type="text" name="task" value="<?php echo $value['title'] ?>">
                                <button type="submit" class="btn" name="modiftache">cliquer ici pour modifier la Tâche</button><br><br>
                                <button type="submit" name="cancel" class="btn">annuler</button>
      </form>
                              
 