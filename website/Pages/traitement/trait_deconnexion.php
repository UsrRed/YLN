<?php
if (session_status() == PHP_SESSION_NONE) session_start(); #Page uniquement accessible lorsque l'on est connecté

$utilisateur = $_SESSION['utilisateur'];
$logs = date('Y-m-d H:i:s') . " - [INFO] - L'utilisateur " . $utilisateur . " vient de se déconnecter.";
shell_exec('echo "' . $logs . '" >> /home/logs/logs.txt');

# Détruit la session de l'utilisateur : 
session_destroy();

session_start();
$_SESSION['status'] = "success";
$_SESSION['message'] = "Vous êtes déconnecté !";
header("Location: /accueil");
#Et ensuite on redirige vers la page d'accueil

exit();
