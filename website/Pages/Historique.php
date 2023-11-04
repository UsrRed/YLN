<?php
session_start();

if (!isset($_SESSION['utilisateur'])) {
	header("Location: /Connexion");
    	exit();
}

include('/home/Pages/configBDD/config.php');

$nom_utilisateur = $_SESSION['utilisateur'];
$req_utilisateur = "SELECT id FROM Utilisateur WHERE nom_utilisateur = '$nom_utilisateur'";
$result_utilisateur = mysqli_query($connexion, $req_utilisateur);
$ligne_utilisateur = mysqli_fetch_assoc($result_utilisateur);
$id_utilisateur = $ligne_utilisateur['id'];

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
	$nombre_pages = intval($divis) +1;

}
#Page actuelle (par défaut, la première page) --> https://stackoverflow.com/questions/47579258/isset-get-page-with-php-correct-usage + deux autres sources présente dans le fichier Favoris

$page_actuelle = isset($_GET['page']) ? $_GET['page'] : 1;

#Limite pour la requête SQL
$limite = ($page_actuelle - 1) * $resultats_par_page; #point de départ de récup des données
#$page_actuelle = 4;
#echo "$limite"; (30 --> ok)
$req_historique = "SELECT * FROM Historique WHERE utilisateur_id = '$id_utilisateur' LIMIT $limite, $resultats_par_page"; #Pour ne récupérer seulement $resultats_par_page (ex : 20) éléments à partir du limite (ex : 10ème)  élément
$resultat_historique = mysqli_query($connexion, $req_historique);
#echo resultat_historique;
?>

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
	<h2>Historique des comparaisons pour <?php echo $_SESSION['utilisateur']; ?> :</h2>
	<br/><br/>
	<table class="table table-bordered">
		<thead>
			<tr><th>Comparaison 1</th><th>Comparaison 2</th><th>Date</th> </tr>
		</thead>
		<tbody>
		<?php
			while ($ligne_histo = mysqli_fetch_assoc($resultat_historique)) { #Pour n'avoir qu'une seule ligne qu'on affiche après et tout ça dans une boucle pour parcourir toute la table
			echo "<tr><td>" . $ligne_histo["comparaison1"] . "</td><td>" . $ligne_histo["comparaison2"] . "</td><td>" . $ligne_histo["date"] . "</td></tr>";
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
</html>
