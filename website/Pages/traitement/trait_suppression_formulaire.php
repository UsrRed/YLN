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

$logs = date('Y-m-d H:i:s') . " - [WARNING] - L'utilisateur " . $nom_utilisateur . " s'est rendu sur la page de suppression de compte.";
shell_exec('echo "' . $logs . '" >> /home/logs/logs.txt');

?>
<?php include('/home/includes/header.php'); ?>

<body class="bg-light">

<div class="container mt-5">
	<h2>Supprimer votre compte <?php echo $nom_utilisateur; ?></h2><br/>
        <?php afficher_etat(); ?>
    <p>Êtes-vous sûr de vouloir supprimer votre compte <?php echo $nom_utilisateur; ?> ? Cette action est
        irréversible.</p><br/>
    <form action="/trait_suppression" method="post">
        <button type="submit" class="btn btn-danger">Supprimer le compte</button>
    </form>
</div>
</body>
</html>
