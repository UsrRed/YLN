<?php

#Plusieurs problème : Cannot delete or update a parent row: a foreign key constraint fails (`nathiotime`.`Favoris`, CONSTRAINT `Favoris_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `Utilisateur` (`id`))
#Cannot delete or update a parent row: a foreign key constraint fails (`nathiotime`.`Historique`, CONSTRAINT `Historique_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `Utilisateur` (`id`))

session_start();

if (!isset($_SESSION['utilisateur_id'])) {
	header("Location: /Connexion");
	exit();
}

include('/home/Pages/configBDD/config.php');

$utilisateur = $_SESSION['utilisateur'];
$utilisateur_id = $_SESSION['utilisateur_id'];

#Il faut d'abord supprimer les favoris, sinon problème et pareil pour l'historique

$suppression_favoris_req = "DELETE FROM Favoris WHERE utilisateur_id = $utilisateur_id";
$suppression_favoris_res = mysqli_query($connexion, $suppression_favoris_req);

$suppression_historique_req = "DELETE FROM Historique WHERE utilisateur_id = $utilisateur_id";
$suppression_historique_res = mysqli_query($connexion, $suppression_historique_req);

$suppression_req = "DELETE FROM Utilisateur WHERE nom_utilisateur = '$utilisateur'";
$suppression_res = mysqli_query($connexion, $suppression_req);

session_destroy();
?>
<script>
alert("Le compte <?php echo $utilisateur; ?> a bien été supprimé");
	window.location.href = "/Inscription";
</script>
<?php

#header("Location: /Connexion");

?>
