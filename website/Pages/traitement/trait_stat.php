<?php
if (session_status() == PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['utilisateur_id'])) {
	if (session_status() == PHP_SESSION_NONE) session_start();
	$_SESSION['status'] = "primary";
	$_SESSION['message'] = "Vous devez être connecté, redirection sur la page de connexion...";
	header("Location: /Connexion");
	exit();
}


#On récup les données et c'est 0 si c'est vide (évite les erreurs et affiche le pie chart comme ça

$pageData = [
	#'Accueil' => isset($_SESSION['page_visits']) ? $_SESSION['page_visits'] : 0,
	'Historique' => isset($_SESSION['nb_historique']) ? $_SESSION['nb_historique'] : 0,
	'Chat' => isset($_SESSION['nb_chat']) ? $_SESSION['nb_chat'] : 0,
	'Favoris' => isset($_SESSION['nb_favoris']) ? $_SESSION['nb_favoris'] : 0,
	'Paramètres' => isset($_SESSION['nb_paramètres']) ? $_SESSION['nb_paramètres'] : 0,
	'FAQ' => isset($_SESSION['nb_faq']) ? $_SESSION['nb_faq'] : 0,
	'Accueil' => isset($_SESSION['nb_accueil']) ? $_SESSION['nb_accueil'] : 0,
];

?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

<?php include('/home/includes/header.php'); ?>

<body class="bg-light">
<br/><br/>
	<div class="container">
		<div class="row">
			<div class="col-md-8 mx-auto">
				<canvas id="myChart" style="width:100%;max-width:600px"></canvas>
			</div>
		</div><br/><br/>
		<div class="row mt-4">
			<div class="col-md-8 mx-auto">
				<canvas id="myChart2" style="width:100%;max-width:600px"></canvas>
			</div>
		</div>
	</div>

<!--<canvas id="myChart" style="width:100%;max-width:600px"></canvas>-->

<script>

var nb_historique = <?php echo $_SESSION['nb_historique']; ?>;
var nb_chat = <?php echo $_SESSION['nb_chat']; ?>;
var nb_favoris = <?php echo $_SESSION['nb_favoris']; ?>;
var nb_paramètres = <?php echo $_SESSION['nb_paramètres']; ?>;
var nb_faq = <?php echo $_SESSION['nb_faq']; ?>;
var nb_accueil = <?php echo $_SESSION['nb_accueil']; ?>;

//document.write(nb_connex);
//document.write(nb_inscri);

//document.write(pageVisitsData);

const xValuesBar = ["Accueil", "Chat", "Historique", "Favoris", "Paramètres", "FAQ"];
const yValuesBar = [nb_accueil, nb_chat, nb_historique, nb_favoris, nb_paramètres, nb_faq];
const barColorsBar = ["red", "green","blue","orange","brown", "purple"];

new Chart("myChart", {
	type: "bar",
	data: {
		labels: xValuesBar,
		datasets: [{
			backgroundColor: barColorsBar,
			data: yValuesBar
		}]
	},
	options: {
		legend: {display: false},
		title: {
			display: true,
			text: "Pages principales de l'application consultées par les utilisateurs"
		}
	}
});
</script>

<br><br><br>

<!--<canvas id="myChart2" style="width:100%;max-width:600px"></canvas>-->

<script>
const xValuesPie = ["Accueil", "Chat", "Historique", "Favoris", "Paramètres", "FAQ"];
const yValuesPie = [nb_accueil, nb_chat, nb_historique, nb_favoris, nb_paramètres, nb_faq];
const barColorsPie = [
	"red",
	"blue",
	"green",
	"black",
	"orange",
	"pink"
];

new Chart("myChart2", {
	type: "pie",
	data: {
		labels: xValuesPie,
		datasets: [{
			backgroundColor: barColorsPie,
			data: yValuesPie
		}]
	},
	options: {
		title: {
			display: true,
			text: "Pages principales de l'application consultées par les utilisateurs"
		}
	}
});
</script>

</body>
</html>
