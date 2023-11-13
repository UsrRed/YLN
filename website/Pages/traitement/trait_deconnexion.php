<?php
if (session_status() == PHP_SESSION_NONE) session_start(); #Page uniquement accessible lorsque l'on est connecté

# Détruit la session de l'utilisateur : 
session_destroy();

session_start();
$_SESSION['status'] = "success";
$_SESSION['message'] = "Vous êtes déconnecté !";
header("Location: /");
#Et ensuite on redirige vers la page d'accueil

exit();
