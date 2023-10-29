<?php
session_start(); #Page uniquement accessible lorsque l'on est connecté

# Détruit la session de l'utilisateur : 
session_destroy();

#Et ensuite on redirige vers la page d'accueil

header("Location: /");
exit();
