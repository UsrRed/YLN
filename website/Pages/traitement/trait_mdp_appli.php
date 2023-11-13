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
<html>

<head>
    <title>Qu'est-ce qu'un mot de passe d'application ?</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body class="bg-light">
<?php include('/home/includes/header.php'); ?>
<div class="container mt-5">
        <?php afficher_etat(); ?>
    <h2>Qu'est-ce qu'un mot de passe d'application ?</h2><br/>
    <p>Le mot de passe d'application que vous saisissez servira à l'envoi d'un mail aux créateurs de l'application
        lorsque vous poserez une question sur la page <b>Support et questions</b>. C'est un mot de passe temporaire qui
        est généré aléatoirement par gmail comportant 16 lettres et qu'il est possible de supprimer à tout instant. De
        plus, il est stocké dans la base de données qui est propre à la machine.</p>
    <br/>
    <p>Si vous avez besoin de poser une question sur l'utilisation de l'application ou autre, il est nécessaire de
        renseigner ce champ. L'envoi de mail se fera via PHPMailer en utilisant le serveur snmp gmail, le port 587 avec
        l'utilisateur votre adresse gmail et mot de passe votre mot de passe d'application.
        <br/>
    <p>Pour générer ce code, rendez-vous sur <a href="https://myaccount.google.com/">https://myaccount.google.com/</a>,
        puis en haut à gauche dans l'onget <u>Sécurité</u>, section <u>Comment vous connecter à Google</u>, cliquez sur
        <u>Validation en deux étapes</u> (qui doit impérativement être activée), scroller tout en bas de la page et dans
        la section <u>Mots de passe des applications</u> créer un nom pour l'application (ex : testSAE) et enfin cliquez
        sur <u>Créer</u>. Une suite de caractère caractérisant votre mot de passe devrait s'afficher. Voici votre mot de
        passe d'application, il ne reste plus qu'à le renseigner dans le champ du formulaire (avec les espaces toutes
        les 4 lettres). </p>
    <br/>

    <a href="/trait_profil" class="btn btn-danger">Compléter votre compte</a>

</div>
</body>

</html>
