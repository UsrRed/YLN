<?php
#Informations de connexion pour la base de données

$serveur = "mysql_maitre"; #Le nom du conteneur (fichier .yaml)
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
	mot_de_passe VARCHAR(255) NOT NULL,
	age INT,
	adresse_email VARCHAR(100),
	mot_de_passe_application VARCHAR(100),
	tentatives_echouees INT DEFAULT 0,
	temps_blocage INT DEFAULT 0,
	date_creation_motdepasse TIMESTAMP DEFAULT CURRENT_TIMESTAMP
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
	date_favoris DATETIME,
	FOREIGN KEY (utilisateur_id) REFERENCES Utilisateur(id),
	FOREIGN KEY (historique_id) REFERENCES Historique(id)
);

CREATE TABLE IF NOT EXISTS Messages (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT,
    texte TEXT,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    like_count INT DEFAULT 0,
    dislike_count INT DEFAULT 0,
    FOREIGN KEY (utilisateur_id) REFERENCES Utilisateur(id)
);

CREATE TABLE IF NOT EXISTS LikesDislikes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT,
    id_message INT,
    like_bool BOOLEAN,
    dislike_bool BOOLEAN,
    FOREIGN KEY (id_utilisateur) REFERENCES Utilisateur(id),
    FOREIGN KEY (id_message) REFERENCES Messages(message_id)
);

CREATE TABLE IF NOT EXISTS FAQ (
	id INT AUTO_INCREMENT PRIMARY KEY,
	utilisateur_id INT NOT NULL,
	objet VARCHAR(100) NOT NULL,
	corps VARCHAR (255) NOT NULL,
	date_submission DATETIME,
	FOREIGN KEY (utilisateur_id) REFERENCES Utilisateur(id)
);

CREATE USER IF NOT EXISTS 'repli'@'172.18.0.144' IDENTIFIED WITH mysql_native_password BY 'RepliMasterSlave2023!';
GRANT REPLICATION SLAVE ON *.* TO 'repli'@'172.18.0.144';
FLUSH PRIVILEGES;";

#Merci à Tony Hulot pour nous avoir aidé à écrire le bout de code suivant et ainsi ne plus avoir d'erreur :  
#A la suite d'erreur type : Commands out of sync; you can't run this command now 

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

if (session_status() == PHP_SESSION_NONE) session_start();


if (!isset($_SESSION['masterLog']) || !isset($_SESSION['logPos'])) {
	$masterStatusreq = "SHOW MASTER STATUS";
	$resultatt = $connexion->query($masterStatusreq);

    
	if ($resultatt && $row = $resultatt->fetch_assoc()) {
		$masterLog = $row['File'];
		$logPos = $row['Position'];

		$_SESSION['masterLog'] = $masterLog;
		$_SESSION['logPos'] = $logPos;
	} else {

		$masterLog = "Erreur lors de la récupération de master_log";
		$logPos = "Erreur lors de la récupération de log_pos";
	}
} else {

	$masterLog = $_SESSION['masterLog'];
	$logPos = $_SESSION['logPos'];
}

#Deuxième connexion mais sur la base de données slave : 

#echo "Log : $masterLog";
#echo "";
#echo "Numéro : $logPos";

#On exécute les requêtes SQL pour la création automatique des tables. Mais vu qu'il y a plusieurs requêtes (pour la création de tables), il faut traiter et stocker le résultats de ces différentes requêtes.

#Aide : 

#https://dev.mysql.com/doc/refman/8.0/en/commands-out-of-sync.html
#https://www.php.net/manual/fr/mysqli.multi-query.php
#https://stackoverflow.com/questions/614671/commands-out-of-sync-you-cant-run-this-command-now


#Test pour l'insert d'un utilisateur pour éviter un bug : 


# Insertion dans la table Utilisateur si la ligne n'existe pas
#$insertUtilisateur = "INSERT IGNORE INTO Utilisateur (nom_utilisateur, mot_de_passe, age, adresse_email, mot_de_passe_application) VALUES ('admin', 'admin', 20, 'admin@sae.rt', 'test test test test')";

# Insertion dans la table FAQ si la ligne n'existe pas
#$insertFAQ = "INSERT IGNORE INTO FAQ (utilisateur_id, objet, corps, date_submission) VALUES (999, 'test', 'test', '2023-11-06 12:35:52')";

#mysqli_query($connexion, $insertFAQ);

?>
