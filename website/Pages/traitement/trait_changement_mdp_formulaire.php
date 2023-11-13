<?php
session_start();
if (!isset($_SESSION['utilisateur_id'])) {
    session_start();
    $_SESSION['status'] = "primary";
    $_SESSION['message'] = "Vous devez être connecté, redirection sur la page de connexion...";
	header("Location: /Connexion");
	exit();
}

$nom_utilisateur = $_SESSION['utilisateur'];

?>

<!DOCTYPE html>
<html>
<head>
<title>SAE501-502-THEOTIME-MARTEL</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">
	<?php include('/home/includes/header.php'); ?>
		<div class="container mt-5">
		<h1 class="mb-4">Changer votre mot de passe <?php echo $nom_utilisateur; ?></h1>
        <?php afficher_etat(); ?>
		<form action="/trait_changement_mdp" method="post">
			<div class="form-group">
				<label for="ancienMotDePasse">Ancien mot de passe :</label>
				<input type="password" class="form-control" id="ancienMotDePasse" name="ancienMotDePasse" required>
			</div>
			<div class="form-group">
				<label for="nouveauMotDePasse">Nouveau mot de passe :</label>
				<input type="password" class="form-control" id="nouveauMotDePasse" name="nouveauMotDePasse" required>
			</div>
			<div class="form-group">
				<label for="confirmationMotDePasse">Confirmez le nouveau mot de passe :</label>
				<input type="password" class="form-control" id="confirmationMotDePasse" name="confirmationMotDePasse" required>
			</div>
			<button type="submit" class="btn btn-danger">Changer le mot de passe</button>
		</form>
	</div>
</body>
</html>
