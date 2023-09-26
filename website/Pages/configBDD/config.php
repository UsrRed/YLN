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

# Variable tables pour la création de tables automatiques. Par défaut, après un podman-compose down, les données dans la base de données sont perdues. Av

$tables = "CREATE DATABASE IF NOT EXISTS nathiotime;

USE nathiotime;

CREATE TABLE IF NOT EXISTS Utilisateur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom_utilisateur VARCHAR(25) NOT NULL,
    mot_de_passe VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS Historique (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    comparaison1 VARCHAR(255) NOT NULL,
    comparaison2 VARCHAR(255) NOT NULL,
    date DATETIME,
    FOREIGN KEY (utilisateur_id) REFERENCES Utilisateur(id)
);

CREATE TABLE IF NOT EXISTS Favoris (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    historique_id INT NOT NULL,
    FOREIGN KEY (utilisateur_id) REFERENCES Utilisateur(id),
    FOREIGN KEY (historique_id) REFERENCES Historique(id)
);";

#Bout de code par Tony Hulot, Lukas Theotime et Nathan Martel : 

# A la suite d'erreur type : Commands out of sync; you can't run this command now 

if (mysqli_multi_query($connexion, $tables)) { #On utilise mysqli_multi_query car il y a plusieurs requêtes à la base de données.
	do { # Boucle pour lire et traiter les résultats des requêtes SQL qui ont été éxécutées avant : 
        	if ($res_ancienne_requete = mysqli_store_result($connexion)) { # On vérifie si des résultats sont disponibles pour les requêtes SQLs précédentes
            		mysqli_free_result($res_ancienne_requete); # On "libère" la mémoire utilisée lors du traitement des anciennes requêtes
        		}
   	   } while (mysqli_next_result($connexion)); 
	echo "";
} else {
    echo "Erreur lors de la création des tables";
}

#On exécute les requêtes SQL pour la création automatique des tables. Mais vu qu'il y a plusieurs requêtes (pour la création de tables), il faut traiter et stocker le résultats de ces différentes requêtes.

#Aide : 

#https://dev.mysql.com/doc/refman/8.0/en/commands-out-of-sync.html
#https://www.php.net/manual/fr/mysqli.multi-query.php
#https://stackoverflow.com/questions/614671/commands-out-of-sync-you-cant-run-this-command-now


?>
