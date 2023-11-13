<?php include('/home/includes/header.php'); ?>

<body class="bg-light">
	<div class="container mt-5">
	<h1>Connexion</h1>
        <?php afficher_etat(); ?>
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
				<button type="submit" class="btn btn-success">Se connecter</button>
				<a href="/trait_deconnexion" class="btn btn-danger">Déconnexion</a>
			</div>

			<small class="form-text text-muted">
				<a href="/trait_mdp_oublie_formulaire">Mot de passe oublié ?</a>
			</small>
		</form>
	</div>
</body>
