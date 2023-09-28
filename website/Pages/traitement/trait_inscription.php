<?php

#Connexion à la base de données :

include('/home/Pages/configBDD/config.php');

#Récupération des donnés du formulaire

$nouv_utilisateur= $_POST['nouv_utilisateur'];
$nouv_motdepasse= $_POST['nouv_motdepasse'];

#Insertion des données dans la base de données

$sql ="INSERT INTO Utilisateur (nom_utilisateur, mot_de_passe) VALUES ('$nouv_utilisateur', '$nouv_motdepasse')";

if (mysqli_query($connexion, $sql)) {
    echo "Inscription réussi !";
} else {
    echo "Erreur lors de l'inscription : ";
}

mysqli_close($connexion);
?>

