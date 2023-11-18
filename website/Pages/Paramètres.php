<?php
if (session_status() == PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['utilisateur'])) {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $_SESSION['status'] = "primary";
        $_SESSION['message'] = "Vous devez être connecté, redirection sur la page de connexion...";
        header("Location: /Connexion");
        exit();
}

include('/home/Pages/configBDD/config.php');

$nom_utilisateur = $_SESSION['utilisateur'];

?>

<?php include('/home/includes/header.php'); ?>

<body class="bg-light">
<div class="container mt-5">
    <h2>Paramètres de <?php echo $_SESSION['utilisateur']; ?> :</h2>
        <?php afficher_etat(); ?>
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
                    <h5><b>Votre profil</b></h5>
                    <p>Cliquez sur le bouton pour compléter les informations de votre profil :</p>
                    <a href="/trait_profil" class="btn btn-danger">Votre profil</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h5><b>Supprimer le compte</b></h5>
                    <p>Cliquez sur le bouton ci-dessous pour supprimer votre compte :</p>
                    <a href="/trait_suppression_formulaire" class="btn btn-danger">Supprimer le compte</a>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-body">
                    <h5><b>Informations</b></h5>
                    <p>Page d'information sur l'application :</p>
                    <a href="/trait_info_application" class="btn btn-danger">Informations sur l'application</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
