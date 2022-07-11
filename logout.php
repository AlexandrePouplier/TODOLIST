<?php
//* Permet le logout direct de la page

session_start();
session_destroy();
header("Location: index.php");

?>