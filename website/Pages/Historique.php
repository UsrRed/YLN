<?php
session_start(); #Pour que seuls les utilisateurs connectés accèdent à la page

if (!isset($_SESSION['utilisateur'])) {
    #L'utilisateur n'est pas connecté, on redirige vers la page de connexion
    header("Location: /Connexion");
    exit();
}

#Connexion à la base de données :
include('/home/Pages/configBDD/config.php');

#Récupération de l'historique des comparaisons en fonction de l'utilisateur connecté
$nom_utilisateur = $_SESSION['utilisateur']; #On récupère le nom de l'utilisateur dans la session

#Requête pour obtenir l'ID de l'utilisateur
$req_utilisateur = "SELECT id FROM Utilisateur WHERE nom_utilisateur = '$nom_utilisateur'";
$result_utilisateur = mysqli_query($connexion, $req_utilisateur);

$ligne_utilisateur = mysqli_fetch_assoc($result_utilisateur);
$id_utilisateur = $ligne_utilisateur['id']; #On récup l'ID ici car le nom de la ligne ID correspond à l'ID de l'utilisateur

#Requête pour obtenir l'historique des comparaisons de l'utilisateur
$req_historique = "SELECT * FROM Historique WHERE utilisateur_id = '$id_utilisateur'";
$resultat_historique = mysqli_query($connexion, $req_historique);

?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>SAE501-502-THEOTIME-MARTEL</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <!-- Pour avoir bootstrap version 4.5.2 : https://getbootstrap.com/docs/4.5/getting-started/introduction/-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </head>
    <body class="bg-light">
    <?php include('/home/includes/header.php'); ?>
    <div class="container mt-5">
    <br/><br/>
    <a href="/trait_deconnexion" class="btn btn-danger">Déconnexion</a>

    <?php
    
    #Affiche l'historique des comparaisons en fonction de l'utilisateur connecté, source : https://lesdocs.fr/lire-et-afficher-une-table-mysql/
    echo "<h2>Historique des comparaisons pour " . $_SESSION['utilisateur'] . " :</h2>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Comparaison 1</th><th>Comparaison 2</th><th>Date</th></tr>";
    while ($ligne_histo= mysqli_fetch_assoc($resultat_historique)) {
        echo "<tr><td>" . $ligne_histo["id"] . "</td><td>" . $ligne_histo["comparaison1"] . "</td><td>" . $ligne_histo["comparaison2"] . "</td><td>" . $ligne_histo["date"] . "</td></tr>";
    }
    echo "</table>";
    
    ?>
    
    </div>
    </body>
    </html>
