<?php

#Connexion à la base de données :

include('/home/Pages/configBDD/config.php');

#Récupération des donnés du formulaire

$nouv_utilisateur = filter_var($_POST['nouv_utilisateur'], FILTER_SANITIZE_STRING);
$nouv_motdepasse = $_POST['nouv_motdepasse'];

# hashage et sallage du mot de passe
$nouv_motdepasse = password_hash($nouv_motdepasse, PASSWORD_DEFAULT);

#Date création du mot de passe
$date_creation_motdepasse = date("Y-m-d H:i:s");
#echo date_creation_motdepasse;

#On vérifie d'abord que l'utilisateur n'existe pas

$verif = "SELECT * FROM Utilisateur WHERE nom_utilisateur = '$nouv_utilisateur'";
$res = mysqli_query($connexion, $verif);

if (mysqli_num_rows($res) > 0) {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $_SESSION['status'] = "warning";
        $_SESSION['message'] = "Cet utilisateur existe déjà";
        header("Location: /Inscription");
} else {

#Insertion des données dans la base de données

        $sql = "INSERT INTO Utilisateur (nom_utilisateur, mot_de_passe, date_creation_motdepasse) VALUES ('$nouv_utilisateur', '$nouv_motdepasse', '$date_creation_motdepasse')";

        if (mysqli_query($connexion, $sql)) {
                if (session_status() == PHP_SESSION_NONE) session_start();
                $_SESSION['status'] = "success";
                $_SESSION['message'] = "Inscription réussie, veuillez-vous connecter";
                header("Location: /Connexion");
        } else {
                if (session_status() == PHP_SESSION_NONE) session_start();
                $_SESSION['status'] = "danger";
                $_SESSION['message'] = "Erreur lors de l'inscription. Essayez à nouveau";
                header("Location: /Inscription");
        }

}

mysqli_close($connexion);
?>
