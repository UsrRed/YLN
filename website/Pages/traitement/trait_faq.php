<?php

session_start();

if (!isset($_SESSION['utilisateur_id'])) {
	header("Location: /Connexion");
	exit();
}

include('/home/Pages/configBDD/config.php');

$utilisateur_id = $_SESSION['utilisateur_id'];

#On vérifuie l'utilisateur connecté. Si c'est admin, on le redirige vers une page où lui seul à accès, si c'est un autre utilisateur, on le redirige vers l'accueil

$req = "SELECT FAQ.*, Utilisateur.adresse_email, Utilisateur.nom_utilisateur FROM FAQ, Utilisateur WHERE FAQ.utilisateur_id = Utilisateur.id AND FAQ.utilisateur_id = '$utilisateur_id'";

$resultat = $connexion->query($req);

$par_ligne = $resultat->fetch_assoc();
$nom_utilisateur = $par_ligne['nom_utilisateur'];

if ($nom_utilisateur !== 'admin') {
	#Là, on redirige l'utilisateur vers la page d'accueil car ce n'est pas l'admin
	#header("Location: /");
	?>
	<script>
		alert("Vous n'êtes pas autorisé à accéder à cette page. Seul l'admin peut y aller. Redirection vers la page d'accueil...");
		window.location.href = "/";
	</script>
	<?php
	exit();
} else {
	#Là, on redirige l'utilisateur qui est donc "admin" vers la page "admin.php" qui est un fichier où lui seul à accès
	header("Location: /admin");
	exit();
}

