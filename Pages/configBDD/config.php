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

#$sql ="CREATE DATABASE nathiotime; USE nathiotime; CREATE TABLE Utilisateur ( id INT AUTO_INCREMENT PRIMARY KEY, nom_utilisateur VARCHAR(25) NOT NULL, mot_de_passe VARCHAR(50) NOT NULL ); CREATE TABLE Historique ( id INT AUTO_INCREMENT PRIMARY KEY, utilisateur_id INT NOT NULL, comparaison1 VARCHAR(50) NOT NULL, comparaison2 VARCHAR(50) NOT NULL, date DATETIME, FOREIGN KEY (utilisateur_id) REFERENCES Utilisateur(id) ); CREATE TABLE Favoris ( id INT AUTO_INCREMENT PRIMARY KEY, utilisateur_id INT NOT NULL, historique_id INT NOT NULL, FOREIGN KEY (utilisateur_id) REFERENCES Utilisateur(id), FOREIGN KEY (historique_id) REFERENCES Historique(id) );";


 


?>

