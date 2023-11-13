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
    session_start();
    $_SESSION['status'] = "warning";
    $_SESSION['message'] = "Cet utilisateur existe déjà";
    header("Location: /Inscription");
} else {

#Insertion des données dans la base de données

$sql ="INSERT INTO Utilisateur (nom_utilisateur, mot_de_passe) VALUES ('$nouv_utilisateur', '$nouv_motdepasse')";

}

if (mysqli_query($connexion, $sql)) {
    session_start();
    $_SESSION['status'] = "success";
    $_SESSION['message'] = "Inscription réussie, veuillez-vous connecter";
    header("Location: /Connexion");
} else {
    session_start();
    $_SESSION['status'] = "danger";
    $_SESSION['message'] = "Erreur lors de l'inscription. Essayez à nouveau";
    header("Location: /Inscription");
}
mysqli_close($connexion);
?>
