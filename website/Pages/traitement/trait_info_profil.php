<?php
session_start();
if (!isset($_SESSION['utilisateur_id'])) {
    session_start();
    $_SESSION['status'] = "primary";
    $_SESSION['message'] = "Vous devez être connecté, redirection sur la page de connexion...";
	header("Location: /Connexion");
	exit();
}

include('/home/Pages/configBDD/config.php');

$age = $_POST['age'];
$email = $_POST['email'];
$app_password = $_POST['app_password'];
$utilisateur_id = $_SESSION['utilisateur_id']; #ID de l'utilisateur connecté


#Met à jour les données de l'utilisateur

$update_query = "UPDATE Utilisateur SET age = '$age', adresse_email = '$email', mot_de_passe_application = '$app_password' WHERE id = '$utilisateur_id'";

#Exécute la requête
if ($connexion->query($update_query) === TRUE) {
	#echo "Données mises à jour avec succès.";
    session_start();
    $_SESSION['status'] = "success";
    $_SESSION['message'] = "Les données ont été mises à jour avec succès";
    header("Location: /Paramètres");
} else {
	#echo "Erreur lors de la mise à jour des données : " . $connexion->error;
    session_start();
    $_SESSION['status'] = "danger";
    $_SESSION['message'] = "Erreur lors de la mise à jour des données";
    header("Location: /trait_profil");
}

// Fermez la connexion à la base de données
$connexion->close();
?>

