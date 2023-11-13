<?php

if (session_status() == PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['utilisateur_id'])) {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $_SESSION['status'] = "primary";
        $_SESSION['message'] = "Vous devez être connecté, redirection sur la page de connexion...";
        header("Location: /Connexion");
        exit();
}

include('/home/Pages/configBDD/config.php');

$utilisateur_id = $_SESSION['utilisateur_id'];

#On vérifuie l'utilisateur connecté. Si c'est admin, on le redirige vers une page où lui seul à accès, si c'est un autre utilisateur, on le redirige vers l'accueil

$req = "SELECT FAQ.*, Utilisateur.adresse_email, Utilisateur.nom_utilisateur FROM FAQ, Utilisateur WHERE FAQ.utilisateur_id = Utilisateur.id";

$resultat = $connexion->query($req);

$par_ligne = $resultat->fetch_assoc();

$nom_utilisateur = $_SESSION['utilisateur'];

if ($nom_utilisateur != 'admin') {
        #Là, on redirige l'utilisateur vers la page d'accueil car ce n'est pas l'admin
        #header("Location: /");
        echo "<br/>--> Si vous êtes connecté en tant qu'admin et que vous obtenez ce message : <br/><br/>1)Rendez-vous sur la page 'support et questions' en étant connecté en tant qu'admin <br/>2)Envoyez n'importe qu'elle objet et n'importe qu'elle corps en saisissant au moins un caractère dans les deux champs du formulaire<br/>3) Cliquez sur ok pour fermer l'alerte après avoir soumis le formulaire<br/>4)Cliquez sur l'onglet FAQ et cela devrait fonctionner";
        if (session_status() == PHP_SESSION_NONE) session_start();
        $_SESSION['status'] = "warning";
        $_SESSION['message'] = "Vous n'êtes pas autorisé à accéder à cette page. Seul l'admin peut y accéder.";
        header("Location: /");
        exit();
} else {
        #Là, on redirige l'utilisateur qui est donc "admin" vers la page "admin.php" qui est un fichier où lui seul à accès
        header("Location: /admin");
        exit();
}
