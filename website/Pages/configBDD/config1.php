<?php

if (session_status() == PHP_SESSION_NONE) session_start();

// Récupérer les valeurs de logPos de la session
$masterLog = $_SESSION['masterLog'];
$logPos = $_SESSION['logPos'];

echo $logPos;
echo "test : $masterLog";

$hostSlave = "mysql_esclave";
$portSlave = 3306;
$userSlave = "root";
$passwordSlave = "root";
$databaseSlave = "nathiotime";

$connSlave = new mysqli($hostSlave, $userSlave, $passwordSlave, $databaseSlave, $portSlave);

// Vérifier la connexion
if ($connSlave->connect_error) {
    die("La connexion à la base de données esclave a échoué : " . $connSlave->connect_error);
}

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
);";





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



// Récupérer les valeurs de logPos de la session
#$masterLog = $_SESSION['masterLog'];
#$logPos = $_SESSION['logPos'];

#echo $logPos;
#echo "test : $masterLog";

// Commandes pour configurer la réplication sur la deuxième base de données
$stop = "STOP REPLICA IO_THREAD";
$changeMasterQuery = "CHANGE MASTER TO MASTER_HOST='172.18.0.5', MASTER_USER='repli', MASTER_PASSWORD='votre_mot_de_passe', MASTER_LOG_FILE='$masterLog', MASTER_LOG_POS=$logPos";
$skipCounterQuery = "SET GLOBAL SQL_SLAVE_SKIP_COUNTER = 1";
$start = "START REPLICA IO_THREAD";
$startIoThreadQuery = "START SLAVE IO_THREAD";
$startSqlThreadQuery = "START SLAVE SQL_THREAD";

// Exécuter les commandes sur la deuxième base de données
$connSlave->query($stop);
$connSlave->query($changeMasterQuery);
$connSlave->query($skipCounterQuery);
$connSlave->query($start);
$connSlave->query($startIoThreadQuery);
$connSlave->query($startSqlThreadQuery);


$connSlave->close();
?>

