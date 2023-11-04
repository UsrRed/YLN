<!DOCTYPE html>
<html>

<head>
	<title>SAE501-502-THEOTIME-MARTEL</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<!-- Pour avoir bootstrap version 4.5.2 : https://getbootstrap.com/docs/4.5/getting-started/introduction/-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body class="bg-light">
	<?php include('/home/includes/header.php'); ?>
	<div class="container mt-5">
	<h1>Connexion</h1>
		<form action="/trait_connexion" method="post">
			<div class="form-group">
				<label for="utilisateur">Nom d'utilisateur :</label>
				<input type="text" class="form-control" id="utilisateur" name="utilisateur" placeholder="Entrez votre nom d'utilisateur" required>
			</div>

			<div class="form-group">
				<label for="motdepasse">Mot de passe :</label>
				<input type="password" class="form-control" id="motdepasse" name="motdepasse" placeholder="Entrez votre mot de passe" required>
			</div>

			<div class="d-flex justify-content-between"> 
		<!--Pour que ce soit aligné avec le formulaire, a droite, source : https://getbootstrap.com/docs/4.0/utilities/flex/ -->
				<button type="submit" class="btn btn-danger">Se connecter</button>
				<a href="/trait_deconnexion" class="btn btn-danger">Déconnexion</a>
			</div>
		</form>
	</div>
</body>

</html>
