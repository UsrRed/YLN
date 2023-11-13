<?php
if (session_status() == PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['utilisateur_id'])) {
        if (session_status() == PHP_SESSION_NONE) session_start();
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
    <title>Supprimer le compte</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">
<?php include('/home/includes/header.php'); ?>
<div class="container mt-5">
    <h1 class="mb-4">Supprimer le compte</h1>
        <?php afficher_etat(); ?>
    <p>Êtes-vous sûr de vouloir supprimer votre compte <?php echo $nom_utilisateur; ?> ? Cette action est
        irréversible.</p>
    <form action="/trait_suppression" method="post">
        <button type="submit" class="btn btn-danger">Supprimer le compte</button>
    </form>
</div>
</body>
</html>
