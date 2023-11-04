<?php
session_start();

if (!isset($_SESSION['utilisateur'])) {
	header("Location: /Connexion");
	exit();
}

include('/home/Pages/configBDD/config.php');

$nom_utilisateur = $_SESSION['utilisateur'];

?>

<!DOCTYPE html>
<html>
<head>
	<title>SAE501-502-THEOTIME-MARTEL</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body class="bg-light">
	<?php include('/home/includes/header.php'); ?>
	<div class="container mt-5">
	<h2>Paramètres de <?php echo $_SESSION['utilisateur']; ?> :</h2>
	<br/>
		<div class="row">
			<div class="col-md-6">
				<div class="card mb-4">
					<div class="card-body">
					<h5><b>Changer le mot de passe</b></h5>
					<p>Cliquez sur le bouton pour changer votre mot de passe :</p>
					<a href=/trait_changement_mdp_formulaire class="btn btn-danger">Changer le mot de passe</a>
					</div>
				</div>
			<div class="card mb-4">
				<div class="card-body">
					<h5><b>Changer de thème</b></h5>
					<p>Cliquez sur le bouton pour choisir entre thème clair et sombre :</p>
					<a href="/trait_changement_theme" class="btn btn-danger">Changer de thème</a>
				</div>
			</div>
			</div>
			<div class="col-md-6">
				<div class="card mb-4">
					<div class="card-body">
					<h5><b>Supprimer le compte</b></h5>
					<p>Cliquez sur le bouton ci-dessous pour supprimer votre compte :</p>
					<a href="/trait_suppression" class="btn btn-danger">Supprimer le compte</a>
				</div>
			</div>
			<div class="card mb-4">
				<div class="card-body">
					<h5><b>Support</b></h5>
					<p>Accédez à notre support, posez votre question.</p>
					<a href="/trait_support" class="btn btn-danger">Support et questions</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
