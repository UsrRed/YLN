<?php

#Connexion à la base de données :

include('/home/Pages/configBDD/config.php');

#Récupération des donnés du formulaire

$nouv_utilisateur = filter_var($_POST['nouv_utilisateur'], FILTER_SANITIZE_STRING);
$nouv_motdepasse = $_POST['nouv_motdepasse'];


$message = "";

function message_evolutif(&$message, $texte){
        if ($message==""){
                $message .= $texte;
        } else {
                $message .= "<br>" . $texte;
        }
}

# Filtre entrée nom d'utilisateur
if (3 >= strlen($nouv_utilisateur) && strlen($nouv_utilisateur) >= 25){
        message_evolutif($message, "Le nom d'utilisateur doit faire entre 3 et 25 caractères");
}
# Filtres mot de passe
if (8 >= strlen($nouv_motdepasse) && strlen($nouv_motdepasse) >= 100){
        message_evolutif($message, "Le mot de passe doit faire entre 8 et 100 caractères");
}
# regex trouvé sur internet pour au minimum un caractère spécial, une majuscule, une minuscule et un chiffre
# au moins un caractère spécial
if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $nouv_motdepasse)){
        message_evolutif($message, "Le mot de passe doit contenir au minimum un caractère spécial");
}
# au moins une majuscule
if (!preg_match('/[A-Z]/', $nouv_motdepasse)){
        message_evolutif($message, "Le mot de passe doit contenir au minimum une majuscule");
}
# au moins une minuscule
if (!preg_match('/[a-z]/', $nouv_motdepasse)){
        message_evolutif($message, "Le mot de passe doit contenir au minimum une minuscule");
}
# au moins un chiffre
if (!preg_match('/\d/', $nouv_motdepasse)){
        message_evolutif($message, "Le mot de passe doit contenir au minimum un chiffre");
}
if ($message != ""){
        if (session_status() == PHP_SESSION_NONE) session_start();
        $_SESSION['status'] = "warning";
        $_SESSION['message'] = $message;
        header("Location: /Inscription");
        exit(1);
}

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
