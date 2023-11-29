<?php

#Plusieurs problème : Cannot delete or update a parent row: a foreign key constraint fails (`nathiotime`.`Favoris`, CONSTRAINT `Favoris_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `Utilisateur` (`id`))
#Cannot delete or update a parent row: a foreign key constraint fails (`nathiotime`.`Historique`, CONSTRAINT `Historique_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `Utilisateur` (`id`))

if (session_status() == PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['utilisateur_id'])) {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $_SESSION['status'] = "primary";
        $_SESSION['message'] = "Vous devez être connecté, redirection sur la page de connexion...";
        header("Location: /Connexion");
        exit();
}

include('/home/Pages/configBDD/config.php');

$utilisateur = $_SESSION['utilisateur'];
$utilisateur_id = $_SESSION['utilisateur_id'];

#Il faut d'abord supprimer les favoris, sinon problème et pareil pour l'historique

$suppression_likes_dislikes_req = "DELETE FROM LikesDislikes WHERE id_utilisateur = $utilisateur_id";
$suppression_likes_dislikes_res = mysqli_query($connexion, $suppression_likes_dislikes_req);

$suppression_messages_req = "DELETE FROM Messages WHERE utilisateur_id = $utilisateur_id";
$suppression_messages_res = mysqli_query($connexion, $suppression_messages_req);

$suppression_faq_req = "DELETE FROM FAQ WHERE utilisateur_id = $utilisateur_id";
$suppression_faq_rey = mysqli_query($connexion, $suppression_faq_req);

$suppression_favoris_req = "DELETE FROM Favoris WHERE utilisateur_id = $utilisateur_id";
$suppression_favoris_res = mysqli_query($connexion, $suppression_favoris_req);

$suppression_historique_req = "DELETE FROM Historique WHERE utilisateur_id = $utilisateur_id";
$suppression_historique_res = mysqli_query($connexion, $suppression_historique_req);

$suppression_req = "DELETE FROM Utilisateur WHERE nom_utilisateur = '$utilisateur'";
$suppression_res = mysqli_query($connexion, $suppression_req);

session_destroy();
if (session_status() == PHP_SESSION_NONE) session_start();
$_SESSION['status'] = "success";
$_SESSION['message'] = "Le compte " . $utilisateur . " a bien été supprimé";

$logs = date('Y-m-d H:i:s') . " - [CRITICAL] - L'utilisateur " . $utilisateur . " vient de supprimer son compte.";
shell_exec('echo "' . $logs . '" >> /home/logs/logs.txt');

header("Location: /Inscription");
?>
