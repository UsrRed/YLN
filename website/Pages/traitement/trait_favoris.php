<?php
session_start();
if (!isset($_SESSION['utilisateur_id'])) {
	header("Location: /Connexion");
	exit();
}

include('/home/Pages/configBDD/config.php');

if (isset($_POST['ajouter_favoris'])) {
	$id_utilisateur = $_SESSION['utilisateur_id'];
	$comparaison_id = $_POST['comparaison_id'];

	#echo $id_utilisateur; 
	#echo "";
	#echo $comparaison_id;
	#Pour avoir la comparaison dans la table des favris

	$req_favo = "INSERT INTO Favoris (utilisateur_id, historique_id, date_favoris) VALUES ('$id_utilisateur', '$comparaison_id', NOW())";
	mysqli_query($connexion, $req_favo);

	#Et on redirige vers une page favoris
	header("Location: /Favoris");
	exit();
}
?>
