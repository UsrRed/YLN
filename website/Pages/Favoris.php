<?php
session_start();
if (!isset($_SESSION['utilisateur_id'])) {
	header("Location: /Connexion");
	exit();
}

include('/home/Pages/configBDD/config.php');

#Récup de l'ID de l'utilisateur actuel
$id_utilisateur = $_SESSION['utilisateur_id'];

#Requête pour récupérer les favoris de l'utilisateur avec jonction avec la table Historique pour avoir les deux comparaisons.
$req_favoris = "SELECT Favoris.*, Historique.comparaison1, Historique.comparaison2
		FROM Favoris, Historique
		WHERE Favoris.historique_id = Historique.id AND Favoris.utilisateur_id = '$id_utilisateur'";
$resultat_favoris = mysqli_query($connexion, $req_favoris);

#Utilise la même technique de pagination que pour la page historique, commentaires pour explication sur le fichier Historique.php 
$total_fav = mysqli_num_rows($resultat_favoris); 
$fav_par_page = 10;
$divis = $total_fav / $fav_par_page;
#echo "test";
#echo $divis;
if (is_int($divis)) {
	$nombre_page = $divis;
} else {
	$nombre_page = intval($divis) +1;
}

#echo "$nombre_page";

$page_actuelle = isset($_GET['page']) ? $_GET['page'] : 1; #aide de https://nouvelle-techno.fr/articles/mettre-en-place-une-pagination-en-php
#echo "$page_actuelle";
$limite = ($page_actuelle - 1) * $fav_par_page;
$req_favoris_pagination = $req_favoris . " LIMIT $limite, $fav_par_page"; #Pour les limites : https://zestedesavoir.com/tutoriels/351/paginer-avec-php-et-sql --> grandement aidé avec ceci
$resultat_favoris_pagination = mysqli_query($connexion, $req_favoris_pagination);

?>

<!DOCTYPE html>
<html>
<head>
	<title>SAE501-502-THEOTIME-MARTEL</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body class="bg-light">
<?php include('/home/includes/header.php'); ?>
<div class="container mt-5">
	<h2>Favoris des comparaisons pour <?php echo $_SESSION['utilisateur']; ?> :</h2>
	<br/><br/>
	<table class="table table-bordered">
		<thead>
			<tr><th>Comparaison 1</th><th>Comparaison 2</th><th>Afficher</th><th>Date</th></tr>
		</thead>
		<tbody>
		<?php
	while ($ligne_favori = mysqli_fetch_assoc($resultat_favoris_pagination)) {
		echo "<tr>";
		echo "<td>" . $ligne_favori["comparaison1"] . "</td>";
		echo "<td>" . $ligne_favori["comparaison2"] . "</td>";
		echo "<td>";
		echo '<div class="text-center mt-3">';
	        echo '<form method="post" action="/trait_comparaison">';
		echo "<input type='hidden' name='comparaison1' id='comparaison1' value='" . $ligne_favori["comparaison1"] . "' />";
        	echo "<input type='hidden' name='comparaison2' id='comparaison2' value='" . $ligne_favori["comparaison2"] . "' />";
		echo '<button type="submit" class="btn btn-info" name="ajouter_favoris">Voir</button>';
        	echo '</form>';
        	echo '</div>';
		echo "</td>";
		echo "<td>" . $ligne_favori["date_favoris"] . "</td>";
		echo "</tr>";
		}
		?>
		</tbody>
	</table>

	<div class="pagination">
	<?php
	#echo "$nombre_page";
	for ($page = 1; $page <= $nombre_page; $page++) {
		#echo "$page";
		echo '<a href="?page=' . $page . '" class="btn btn-outline-primary">' . $page . '</a>';
		echo "&ensp;";
		#echo "test espace";
		}
	?>
	</div>
</div>
</body>
</html>
