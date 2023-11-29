<?php

if (session_status() == PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['utilisateur_id'])) {
    $_SESSION['status'] = "primary";
    $_SESSION['message'] = "Vous devez être connecté, redirection sur la page de connexion...";
    header("Location: /Connexion");
    exit();
}

include('/home/Pages/configBDD/config.php');


$historique_id = $_POST['historique_id'];

    
$requete_verif_favoris = $connexion->prepare("SELECT historique_id FROM Favoris WHERE historique_id = ?");
$requete_verif_favoris->bind_param("i", $historique_id);
$requete_verif_favoris->execute();
$resultat_verif_favoris = $requete_verif_favoris->get_result();

if ($resultat_verif_favoris->num_rows > 0) {
        
	$requete_suppression_favoris = $connexion->prepare("DELETE FROM Favoris WHERE historique_id = ?");
	$requete_suppression_favoris->bind_param("i", $historique_id);
	$requete_suppression_favoris->execute();
	$requete_suppression_favoris->close();
}

$_SESSION['status'] = "success";
$_SESSION['message'] = "Suppression du favori avec succès";

$utilisateur = $_SESSION['utilisateur'];
$logs = date('Y-m-d H:i:s') . " - [INFO] - L'utilisateur " . $utilisateur . " vient de supprimer une comparaison mise en favoris.";
shell_exec('echo "' . $logs . '" >> /home/logs/logs.txt');

header("Location: /Favoris"); 
 
$connexion->close();
?>

