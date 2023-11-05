<?php
#Connexion à la base de données :

include('/home/Pages/configBDD/config.php');

# Pour récupérer les données du formulaire
$utilisateur = $_POST['utilisateur'];
$motdepasse = $_POST['motdepasse'];

#$message = "";

# Vérification si l'utilisateur existe bien
$req = "SELECT * FROM Utilisateur WHERE nom_utilisateur='$utilisateur' AND mot_de_passe='$motdepasse'";

$resul = mysqli_query($connexion, $req);

if ($resul) {
	# Vérification si une ligne dans la BDD a été trouvée
	if (mysqli_num_rows($resul) == 1) {
		#echo "connecté, c'est good";
		# Si 1, l'utilisateur est connecté, c'est ok
		$par_ligne = mysqli_fetch_assoc($resul);
		$id_utilisateur = $par_ligne['id']; # Récupère l'identifiant de l'utilisateu
		#echo "id_utilisateur";
		session_start(); # On démarre sa session
		$_SESSION['utilisateur_id'] = $id_utilisateur; # Stocke l'ID de l'utilisateur dans la session
		$_SESSION['utilisateur'] = $utilisateur;
		?>
		<script>
			alert("Connexion réussie, bienvenue sur l'application");
			window.location.href = "/";
		</script>
		<?php

		#header("Location: /");
		exit();
	} else {
		# Si la variable resul n'est pas de 1, l'utilisateur n'existe pas et n'est donc pas connecté
		
		#echo "Erreur de connexion à la base de données : " . mysqli_connect_error();
?>


		<script>
			alert("Vous n'êtes pas connecté, les informations de connexion sont incorrectes");
			window.location.href = "/Connexion";
		</script>
	<?php
		#$message = "Vous n'êtes pas connecté, les informations de connexion sont incorrectes";
		}
} else {
	# Erreur de requête
	$message = "Erreur de requête : " . mysqli_error($connexion);
}

#echo $message;
?>
