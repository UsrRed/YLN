<?php

if (session_status() == PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['utilisateur_id'])) {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $_SESSION['status'] = "primary";
        $_SESSION['message'] = "Vous devez être connecté, redirection sur la page de connexion...";
        header("Location: /Connexion");
        exit();
}


include('/home/Pages/configBDD/config.php');

$historique_id = $_POST['comparaison_id'];

if (filter_var($historique_id, FILTER_VALIDATE_INT)) {
	#Vu que Favoris et Historique sont lié avec la clef, par moment ca faisait ça : Message: Cannot delete or update a parent row: a foreign key constraint fails (`nathiotime`.`Favoris`, CONSTRAINT `Favoris_ibfk_2` FOREIGN KEY (`historique_id`) REFERENCES `Historique` (`id`))    
	$requete_verif_favoris = $connexion->prepare("SELECT historique_id FROM Favoris WHERE historique_id = ?");
	$requete_verif_favoris->bind_param("i", $historique_id);
	$requete_verif_favoris->execute();
	$resultat_verif_favoris = $requete_verif_favoris->get_result();

	if ($resultat_verif_favoris->num_rows > 0) {
	#Si justement c'est dans Favoris, faut suppriler
	$requete_suppression_favoris = $connexion->prepare("DELETE FROM Favoris WHERE historique_id = ?");
	$requete_suppression_favoris->bind_param("i", $historique_id);
	$requete_suppression_favoris->execute();
	$requete_suppression_favoris->close();
}
	#Là on supprile l'historique
	$requete_suppression = $connexion->prepare("DELETE FROM Historique WHERE id = ?");
	$requete_suppression->bind_param("i", $historique_id);
	$requete_suppression->execute();
	$requete_suppression->close();

	$_SESSION['status'] = "success";
	$_SESSION['message'] = "Suppression de la ligne avec succès. Si la ligne ne s'est pas supprimée, c'est parce qu'il y avait une doublure.";
	$utilisateur = $_SESSION['utilisateur'];

	$logs = date('Y-m-d H:i:s') . " - [INFO] - L'utilisateur " . $utilisateur . " vient de supprimer une ligne de l'historique de ses comparaisons.";
	shell_exec('echo "' . $logs . '" >> /home/logs/logs.txt');
	
	header("Location: /Historique");
}

$connexion->close();
?>
