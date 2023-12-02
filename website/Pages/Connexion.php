<?php include('/home/includes/header.php'); 

if (!isset($_SESSION['nb_connex'])) {
    $_SESSION['nb_connex'] = 1;
}
$_SESSION['nb_connex']++;

?>

<body class="bg-light">
<div class="container mt-5">
    <h1>Connexion</h1>
        <?php afficher_etat(); ?>
    <form action="/trait_connexion" method="post">
        <div class="form-group">
            <label for="utilisateur">Nom d'utilisateur :</label>
            <input type="text" class="form-control" id="utilisateur" name="utilisateur"
                   placeholder="Entrez votre nom d'utilisateur" required>
        </div>

        <div class="form-group">
            <label for="motdepasse">Mot de passe :</label>
            <input type="password" class="form-control" id="motdepasse" name="motdepasse"
                   placeholder="Entrez votre mot de passe" required>
	</div>
	<!--<small class="form-text text-muted">
		<a href="/Inscription">Pas de compte ?</a>
	</small>
	<br/>-->
        <div class="d-flex justify-content-between">
            <!--Pour que ce soit aligné avec le formulaire, a droite, source : https://getbootstrap.com/docs/4.0/utilities/flex/ -->
            <button type="submit" class="btn btn-success">Se connecter</button>
            <?php if (isset($_SESSION['utilisateur_id'])) { # Si l'utilisateur est connécté, il peux se déconnecter. ?>
            <a href="/trait_deconnexion" class="btn btn-danger">Déconnexion</a>
            <?php } ?>
        </div>


        <small class="form-text text-muted">
            <a href="/trait_mdp_oublie_formulaire">Mot de passe oublié ?</a>
        </small>
    </form>
</div>
</body>
