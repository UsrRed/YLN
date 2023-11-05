<?php
session_start();
if (!isset($_SESSION['utilisateur_id'])) {
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
	?>
	<script>
		alert("Les données ont été mises à jour avec succès");
		window.location.href = "/Paramètres";
	</script>
	<?php
} else {
	#echo "Erreur lors de la mise à jour des données : " . $connexion->error;
	?>
	<script>
		alert("Erreur lors de la mise à jour des données");
		window.location.href = "/trait_profil";
		</script>
	<?php
}

// Fermez la connexion à la base de données
$connexion->close();
?>

