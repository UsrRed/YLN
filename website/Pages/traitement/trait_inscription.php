<?php

#Connexion à la base de données :

include('/home/Pages/configBDD/config.php');

#Récupération des donnés du formulaire

$nouv_utilisateur= $_POST['nouv_utilisateur'];
$nouv_motdepasse= $_POST['nouv_motdepasse'];

#On vérifie d'abord que l'utilisateur n'existe pas

$verif = "SELECT * FROM Utilisateur WHERE nom_utilisateur = '$nouv_utilisateur'";
$res = mysqli_query($connexion, $verif);

if(mysqli_num_rows($res) > 0) {
?>
        <script>
                alert("Cet utilisateur existe déjà");
                window.location.href= "/Inscription";
        </script>
<?php
} else {

#Insertion des données dans la base de données

$sql ="INSERT INTO Utilisateur (nom_utilisateur, mot_de_passe) VALUES ('$nouv_utilisateur', '$nouv_motdepasse')";

}

if (mysqli_query($connexion, $sql)) {
	?>
	<script>
		alert("Inscription réussie, veuillez-vous connecter");
		window.location.href = "/Connexion";
	</script>		
	<?php
	#header("Location: /Connexion");
} else {
?>
	<script>
		alert("Erreur lors de l'inscription. Essayez à nouveau";
		window.location.href = "/Inscription";
	</script>
	<?php
	#echo "Erreur lors de l'inscription. Essayez à nouveau";
}
#}
mysqli_close($connexion);
?>
