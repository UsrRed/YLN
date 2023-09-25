<?php
#Informations de connexion pour la base de données

$serveur = "mysql"; #Le nom du conteneur (fichier .yaml)
$port = 3306; #Le port utilisé dans le fichier .yaml
$utilisateur = "root";
$motdepasse = "root";
$basededonnees = "nathiotime";

#Connexion sur la base de données
$connexion = mysqli_connect($serveur, $utilisateur, $motdepasse, $basededonnees, $port);

if (!$connexion) {
    die("La connexion à la base de données a échoué : " . mysqli_connect_error());
}

?>

