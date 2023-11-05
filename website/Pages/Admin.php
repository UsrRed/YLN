<?php
session_start();

if (!isset($_SESSION['utilisateur_id'])) {
	header("Location: /Connexion");
	exit();
}

include('/home/Pages/configBDD/config.php');

$utilisateur_id = $_SESSION['utilisateur_id'];

$req_faq = "SELECT FAQ.*, Utilisateur.adresse_email, Utilisateur.nom_utilisateur FROM FAQ, Utilisateur WHERE FAQ.utilisateur_id = Utilisateur.id";
$resultat_faq = $connexion->query($req_faq);

#Pour avoir le nombre total des lignes dans le tableau
$total_resultats = mysqli_num_rows($resultat_faq);

#Nombre maxmum de érsultats par page
$resultats_par_page = 10;

#Nombre total de pages (on prend l'entier et on ajoute +1 si c'est un float, pas réussi a utiliser ceil, reregarder, ce serait plus simple
$divis = $total_resultats / $resultats_par_page;
#On arrondit au nombre suivant
#$nbpage = ceil($total_resultats / resultats_par_page); --> fonctionne pas, inconnu
if (is_int($divis)) {
	$nombre_pages = $divis;
} else {
	$nombre_pages = intval($divis) +1;

}

# Page actuelle (par défaut, la première page)
$page_actuelle = isset($_GET['page']) ? $_GET['page'] : 1;

# Limite pour la requête SQL
$limite = ($page_actuelle - 1) * $resultats_par_page;

# Requête SQL avec la limite
$req_faq = "SELECT FAQ.*, Utilisateur.adresse_email, Utilisateur.nom_utilisateur FROM FAQ, Utilisateur WHERE FAQ.utilisateur_id = Utilisateur.id LIMIT $limite, $resultats_par_page";
$resultat_faq = $connexion->query($req_faq);
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
		<h2>Liste des Questions Fréquemment Posées (FAQ)</h2><br/>
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>Utilisateur</th>
					<th>Adresse mail</th>
					<th>Objet</th>
					<th>Corps</th>
					<th>Date de Soumission</th>
				</tr>
			</thead>
			<tbody>
				<?php
				while ($ligne = $resultat_faq->fetch_assoc()) {
					echo '<tr>';
					echo '<td>' . $ligne['nom_utilisateur'] . '</td>';
					echo '<td>' . $ligne['adresse_email'] . '</td>';
					echo '<td>' . $ligne['objet'] . '</td>';
					echo '<td>' . $ligne['corps'] . '</td>';
					echo '<td>' . $ligne['date_submission'] . '</td>';
					echo '</tr>';
				}
				?>
			</tbody>
		</table>
		<div class="pagination">
			<?php
			for ($page = 1; $page <= $nombre_pages; $page++) {
				echo '<a href="?page=' . $page . '" class="btn btn-outline-primary">' . $page . '</a>';
				echo "&ensp;";
			}
			?>
		</div>
	</div>
</body>
</html>

