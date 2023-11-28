<?php
if (session_status() == PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['utilisateur'])) {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $_SESSION['status'] = "primary";
        $_SESSION['message'] = "Vous devez être connecté, redirection sur la page de connexion...";
        header("Location: /Connexion");
        exit();
}

if (!isset($_SESSION['nb_historique'])) {
    $_SESSION['nb_historique'] = 0;
}
$_SESSION['nb_historique']++;

include('/home/Pages/configBDD/config.php');

$nom_utilisateur = $_SESSION['utilisateur'];
$id_utilisateur = $_SESSION['utilisateur_id'];
#$req_utilisateur = "SELECT id FROM Utilisateur WHERE nom_utilisateur = '$nom_utilisateur'";
#$result_utilisateur = mysqli_query($connexion, $req_utilisateur);
#$ligne_utilisateur = mysqli_fetch_assoc($result_utilisateur);
#$id_utilisateur = $ligne_utilisateur['id'];

#echo $nom_utilisateur;
#echo id_utilisateur;

#Nombre de résultats par page
$resultats_par_page = 10;

$req_historique = "SELECT * FROM Historique WHERE utilisateur_id = '$id_utilisateur'";
$resultat_historique = mysqli_query($connexion, $req_historique);

# Nombre total des lignes dans le tableau, soit le total des résultat
$total_resultats = mysqli_num_rows($resultat_historique);
#echo $total_resultats;

#Nombre total de pages (on prend l'entier et on ajoute +1 si c'est un float, pas réussi a utiliser ceil, reregarder, ce serait plus simple
$divis = $total_resultats / $resultats_par_page;
#On arrondit au nombre suivant
#$nbpage = ceil($total_resultats / resultats_par_page); --> fonctionne pas, inconnu
if (is_int($divis)) {
        $nombre_pages = $divis;
} else {
        $nombre_pages = intval($divis) + 1;

}
#Page actuelle (par défaut, la première page) --> https://stackoverflow.com/questions/47579258/isset-get-page-with-php-correct-usage + deux autres sources présente dans le fichier Favoris

$page_actuelle = isset($_GET['page']) ? $_GET['page'] : 1;

#Limite pour la requête SQL
$limite = ($page_actuelle - 1) * $resultats_par_page; #point de départ de récup des données
#$page_actuelle = 4;
#echo "$limite"; (30 --> ok)
#$req_historique = "SELECT * FROM Historique WHERE utilisateur_id = '$id_utilisateur' ORDER BY date DESC LIMIT $limite, $resultats_par_page"; #Pour ne récupérer seulement $resultats_par_page (ex : 20) éléments à partir du limite (ex : 10ème)  élément
#$req_historique = "SELECT DISTINCT comparaison1,comparaison2, date FROM Historique WHERE utilisateur_id = '$id_utilisateur' ORDER BY date DESC LIMIT $limite, $resultats_par_page";
$req_historique = "SELECT MAX(id) as id, comparaison1, comparaison2, MAX(date) AS date FROM Historique WHERE utilisateur_id = '$id_utilisateur' GROUP BY comparaison1, comparaison2 ORDER BY date DESC LIMIT $limite, $resultats_par_page"; #Pour ne pas avoir de doublure et avoir la date la plus récente
$resultat_historique = mysqli_query($connexion, $req_historique);
#echo resultat_historique;
?>

<?php include('/home/includes/header.php'); ?>

<body class="bg-light">
<div class="container mt-5">
    <h2>Historique des comparaisons pour <?php echo $_SESSION['utilisateur']; ?> :</h2>
    <br/><br/>
        <?php afficher_etat(); ?>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th><div class="text-center">Comparaison 1</div></th>
            <th><div class="text-center">Comparaison 2</div></th>
            <th><div class="text-center">Afficher</div></th>
	    <th><div class="text-center">Date</div></th>
	    <th><div class="text-center">Action</div></th>
        </tr>
        </thead>
        <tbody>
        <?php
        while ($ligne_histo = mysqli_fetch_assoc($resultat_historique)) { #Pour n'avoir qu'une seule ligne qu'on affiche après et tout ça dans une boucle pour parcourir toute la table
                echo "<tr>";
                echo "<td><div class='text-center mt-2'>" . $ligne_histo["comparaison1"] . "</div></td>";
                echo "<td><div class='text-center mt-2'>" . $ligne_histo["comparaison2"] . "</div></td>";
                echo "<td>";
                echo '<div class="text-center mt-2">';
                echo '<form method="post" action="/trait_comparaison">';
                echo "<input type='hidden' name='comparaison1' id='comparaison1' value='" . $ligne_histo["comparaison1"] . "' />";
                echo "<input type='hidden' name='comparaison2' id='comparaison2' value='" . $ligne_histo["comparaison2"] . "' />";
                echo '<button type="submit" class="btn btn-info" name="Voir">Voir</button>';
                echo '</form>';
                echo '</div>';
                echo "</td>";
		echo "<td><div class='text-center mt-2'>" . $ligne_histo["date"] . "</div></td>";
		echo "<td>";
		echo '<div class="text-center mt-2">';
                echo '<form method="post" action="/trait_suppression_historique">';
                echo "<input type='hidden' name='comparaison_id' value='" . $ligne_histo["id"] . "' />";
                echo '<button type="submit" class="btn btn-danger" name="Supprimer"><span aria-hidden="true">&times;</span></button>';
		echo '</form>';
		echo '</div>';
                echo "</td>";
                echo "</tr>";



        }
        ?>
        </tbody>
    </table>

    <div class="pagination">
            <?php
            for ($page = 1; $page <= $nombre_pages; $page++) { #Pour toutes les pages (automatiques en fonction de nb num row du coup normalement)
                    #echo $page;
                    echo '<a href="?page=' . $page . '" class="btn btn-outline-primary">' . $page . '</a>'; #Il fait le ? car let's go j'ai trouvé, il faut spécifier le param de la page sinon ca fonctionne pas !
                    echo "&ensp;";
                    #echo "testtttt";
            }
            ?>
    </div>
</div>
</body>
