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
    <h2>Mot de passe oublié ?</h2>
    <br/><small>Assurez vous d'avoir <b>auparavant complété votre profil</b> sur <a href="/trait_profil">cette page </a>sinon,
        l'envoi de mail et la réinitialisation du mot de passene se fera pas.</small><br/>
    <small>Les champs adresse e-mail sont une vérification de l'identité.</small><br/><br/>
        <?php afficher_etat(); ?>
    <form action="/trait_mdp_oublie" method="post">
        <div class="form-group">
            <label for="utilisateur">Nom d'utilisateur :</label>
            <input type="text" class="form-control" id="utilisateur" name="utilisateur"
                   placeholder="Entrez votre nom d'utilisateur" required>
        </div>

        <div class="form-group">
            <label for="email">Adresse e-mail (Gmail uniquement) :</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Entrez votre adresse e-mail"
                   pattern="[a-z0-9._%+-]+@gmail\.com$" title="Adresse e-mail Gmail uniquement" required>
        </div>

        <div class="form-group">
            <label for="age">Âge :</label>
            <input type="text" class="form-control" id="age" name="age" placeholder="Entrez votre âge" required>
        </div>

        <div class="form-group">
            <label for="motdepasse">Mot de passe d'application :</label>
            <input type="password" class="form-control" id="motdepasse" name="motdepasse"
                   placeholder="Entrez votre mot de passe d'application" required>
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-danger">Réinitialiser son mot de passe</button>
        </div>

    </form>
</div>
</body>

</html>

