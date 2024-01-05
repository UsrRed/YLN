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

if ($nom_utilisateur !== 'admin') {
        header("Location : /accueil");
}

include('/home/includes/header.php'); 

?>

<body class="bg-light">
<div class="container mt-5">
<div class="card" style="height: 40em; overflow-y: auto;">
<div class="card-body">

<h2>Logs de l'applications</h2><br/>

<?php ################################################################ Voir pour ajouter des filtres ? ERROR, CRITICAL, INFO, etc avec strpos si je ne me trompe pas

#if (file_exists("/home/logs/logs.txt")) {
#        $logg = shell_exec("cat /home/logs/logs.txt");
#                echo $logg;
#} else {
#                echo "Pas de logs pour le moment";
#}

if (file_exists("/home/logs/logs.txt")) {
   
        $logg = file_get_contents("/home/logs/logs.txt"); #En chaine de caractères
        $lignes = explode("\n", $logg); #Divise avec les sauts de ligne

        foreach ($lignes as $ligne) {
                echo "$ligne <br>";
        }

} else {
        echo "Pas de logs pour le moment";
}

?>
</div></div>

<br/><br/>

<div class="d-flex justify-content-between">
            <!--Pour que ce soit aligné avec le formulaire, a droite, source : https://getbootstrap.com/docs/4.0/utilities/flex/ -->
            <a href="/Vue_globale" class="btn btn-info">Vue globale</a>
            <a href="/trait_stat" class="btn btn-info">Statistiques de l'application</a>
           
</div>

</div>
</body>
