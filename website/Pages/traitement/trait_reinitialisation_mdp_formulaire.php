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
		<h2>Réinitialisez votre mot de passe <?php echo $_SESSION['utilisateur']; ?></h2>
		<br/>

		<?php
		if (session_status() == PHP_SESSION_NONE) session_start();

		if (isset($_SESSION['status']) && isset($_SESSION['message'])) {
			echo '<div class="alert alert-' . $_SESSION['status'] . '">' . $_SESSION['message'] . '</div>';
			unset($_SESSION['status']);
			unset($_SESSION['message']);
		}
		?>

		<form action="/trait_reinitialisation_mdp" method="post">
			<div class="form-group">
				<label for="nouveau_motdepasse">Nouveau mot de passe :</label>
				<input type="password" class="form-control" id="nouveau_motdepasse" name="nouveau_motdepasse" required>
			</div>

			<div class="form-group">
				<label for="confirmer_motdepasse">Confirmer le mot de passe :</label>
				<input type="password" class="form-control" id="confirmer_motdepasse" name="confirmer_motdepasse" required>
			</div>

			<button type="submit" class="btn btn-danger">Réinitialiser le mot de passe</button>
		</form>
	</div>
</body>
</html>
