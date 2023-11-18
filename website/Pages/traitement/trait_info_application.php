<?php
include('/home/includes/header.php');
include('/home/Pages/configBDD/config.php');

if (session_status() == PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['utilisateur_id'])) {
	$_SESSION['status'] = "primary";
	$_SESSION['message'] = "Vous devez être connecté, redirection sur la page de connexion...";
	header("Location: /Connexion");
	exit();
}

# Informations de l'utilisateur 
$utilisateur_id = $_SESSION['utilisateur_id'];
$requete_info_utilisateur = $connexion->prepare("SELECT * FROM Utilisateur WHERE id = ?");
$requete_info_utilisateur->bind_param("i", $utilisateur_id);
$requete_info_utilisateur->execute();
$resultat_info_utilisateur = $requete_info_utilisateur->get_result();

$info_utilisateur = $resultat_info_utilisateur->fetch_assoc();
$nom_utilisateur = $info_utilisateur['nom_utilisateur'];
$age = $info_utilisateur['age'];
$adresse_email = $info_utilisateur['adresse_email'];
# Numéro de téléphone...    
?>

<body class="bg-light">
	<div class="container mt-5">
		<div class="row">
			<div class="col-md-6">
				<div class="card">
					<div class="card-body">
						<h2 class="card-title">Informations de l'utilisateur :</h2>
						<ul class="list-group">
							<li class="list-group-item">Nom d'utilisateur : <?php echo $nom_utilisateur; ?></li>
							<li class="list-group-item">Âge : <?php echo $age; ?></li>
							<li class="list-group-item">Adresse e-mail : <?php echo $adresse_email; ?></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="card">
					<div class="card-body">
						<h2 class="card-title">Informations sur l'application :</h2>
						<ul class="list-group">
							<li class="list-group-item">Version : 3.0</li>
							<li class="list-group-item">
							<a href="https://prezi.com/view/QH96kdy6QWjb5VYOIaqy/">
							<img src="pf.png" width="25" height="25" style="border-radius: 50%;">
							</a>
							Lukas Théotime
							</li>
							<li class="list-group-item">
							<a href="https://yohann-denoyelle.fr/">
							<img src="pf.png" width="25" height="25" style="border-radius: 50%;">
							</a>
							Yohann Denoyelle
							</li>
							<li class="list-group-item">
							<a href="https://www.canva.com/design/DAFPqyVJ51Q/nPi7qS9b5hwka41fVBV-Yw/view?utm_content=DAFPqyVJ51Q&utm_campaign=designshare&utm_medium=link&utm_source=homepage_design_menu#1">
							<img src="pf.png" width="25" height="25" style="border-radius: 50%;">
							</a>
							Nathan Martel
							</li>
                            			</ul>
					</div>
				</div>
			</div>
		</div>
	</div>

<footer class="footer bg-secondary">
	<div class="container">
		<div class="row">
			<div class="col-md-4 text-start"> <!-- Aligné à gauche -->
				<img src="logo.jpg" style="max-width: 90px; height: 90px;">
			</div>
			<div class="col-md-4 text-center d-flex align-items-center"> <!-- Centré verticalement -->
				<div class="center" style="margin: auto;">
				<span>&copy; Dernière MAJ : 17/11/2023</span><br/>
				<span>Date : <?php echo date("M-Y"); ?></span>
				</div>                
			</div>
			<div class="col-md-4" style="display: flex; flex-direction: row-reverse;"> <!-- Aligné à droite -->
				<div style="display: flex; flex-direction: column;"><div>
					<a href="https://www.linkedin.com/in/yohann-denoyelle/">
					<img src="logo_l.jpg" style="max-width: 30px; height: auto;">
					</a>
					<span>Yohann Denoyelle</span>
				</div>
				<div>
					<a href="https://www.linkedin.com/in/lukas-th%C3%A9otime-3058bb225/">
					<img src="logo_l.jpg" style="max-width: 30px; height: auto;">
					</a>
					<span>Lukas Theotime</span>
				</div>
				<div>
					<a href="https://www.linkedin.com/in/nathan-martel-a62a92290/">
					<img src="logo_l.jpg" style="max-width: 30px; height: auto;">
					</a>
					<span>Nathan Martel</span>
				</div>
			</div>
		</div>
	</div>
</div>

<!--Pour focer à ce que ce soit en bas -->

<style>
    body {
	display: flex;
	flex-direction: column;
	min-height: 100vh;     
	}

    .container {
	flex: 1; 
	}

    .footer {
	background-color: #f8f9fa;
	padding: 20px 0;
	position: relative;
	bottom: 0;
	width: 100%;
	}
</style>
</footer>
</body>
</html>

<?php
$connexion->close();
?>
