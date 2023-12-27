<?php
if (session_status() == PHP_SESSION_NONE) session_start(); # Pour démarrer la session

if (!isset($_SESSION['utilisateur'])) {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $_SESSION['status'] = "primary";
        $_SESSION['message'] = "Vous devez être connecté, redirection sur la page de connexion...";
        header("Location: /Connexion");
        exit();
}
?>

<!DOCTYPE html>

<body class="bg-light">
<?php include('/home/includes/header.php'); ?>
<div class="container mt-5">
    <h2>Complétez votre compte</h2>
        <?php afficher_etat(); ?>
    <form action="/trait_profil_info" method="post">
        <div class="form-group">
            <label for="age">Âge :</label>
            <input type="number" class="form-control" id="age" name="age" placeholder="Entrez votre âge" required>
        </div>

        <div class="form-group">
            <label for="email">Adresse e-mail (Gmail uniquement) :</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Entrez votre adresse e-mail"
                   required pattern="[a-zA-Z0-9._%+-]+@gmail\.com$">
        </div>

        <div class="form-group">
            <label for="app_password">Mot de passe d'application :</label>
            <input type="password" class="form-control" id="app_password" name="app_password"
                   placeholder="Entrez votre mot de passe d'application" required>
            <small class="form-text text-muted"><a href="/trait_mdp_appli">Besoin d'aide ?</a></small>
        </div>

        <button type="submit" class="btn btn-primary">Soumettre</button>
    </form>
</div>
</body>

</html>

