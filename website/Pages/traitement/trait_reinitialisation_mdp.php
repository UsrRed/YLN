<?php
if (session_status() == PHP_SESSION_NONE) session_start();

$nouveau_motdepasse = $_POST['nouveau_motdepasse'];
$confirmer_motdepasse = $_POST['confirmer_motdepasse'];

if ($nouveau_motdepasse === $confirmer_motdepasse) {
	#On vérif que les mots de passe correspondent
	include('/home/Pages/configBDD/config.php');

	$utilisateur_id = $_SESSION['utilisateur_id'];
	$mdp_hash = password_hash($nouveau_motdepasse, PASSWORD_DEFAULT);

	$nouv = "UPDATE Utilisateur SET mot_de_passe = '$mdp_hash', date_creation_motdepasse = NOW()  WHERE id = '$utilisateur_id'";

	if (mysqli_query($connexion, $nouv)) {
		$_SESSION['status'] = "success";
		$_SESSION['message'] = "Votre mot de passe a été réinitialisé avec succès. Vous pouvez maintenant vous connecter avec le nouveau mot de passe.";
		session_destroy(); #On détruit la session pour le forcer à se connecter
	} else {
		$_SESSION['status'] = "danger";
		$_SESSION['message'] = "Erreur lors de la réinitialisation du mot de passe.";
	}
	
} else {
	$_SESSION['status'] = "danger";
	$_SESSION['message'] = "Les mots de passe ne correspondent pas. Veuillez réessayer.";
}
	
header("Location: /Connexion");
exit();
?>
