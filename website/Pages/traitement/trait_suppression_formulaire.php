<?php
session_start();
if (!isset($_SESSION['utilisateur_id'])) {
	header("Location: /Connexion");
	exit();
}

$nom_utilisateur = $_SESSION['utilisateur'];

?>

<!DOCTYPE html>
<html>
<head>
	<title>Supprimer le compte</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">
	<?php include('/home/includes/header.php'); ?>
	<div class="container mt-5">
		<h1 class="mb-4">Supprimer le compte</h1>
		<p>Êtes-vous sûr de vouloir supprimer votre compte <?php echo $nom_utilisateur;?> ? Cette action est irréversible.</p>
		<form action="/trait_suppression" method="post">
			<button type="submit" class="btn btn-danger">Supprimer le compte</button>
		</form>
	</div>
</body>
</html>
