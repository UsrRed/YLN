<?php
#Connexion à la base de données :

$error_message = "Mauvais nom d'utilisateur ou mot de passe !";

include('/home/Pages/configBDD/config.php');

# Pour récupérer les données du formulaire
$utilisateur = filter_var($_POST['utilisateur'], FILTER_UNSAFE_RAW);
$motdepasse = $_POST['motdepasse'];
#$message = "";

# Vérification si l'utilisateur existe bien
$req = "SELECT * FROM Utilisateur WHERE nom_utilisateur='$utilisateur'";

$resul = mysqli_query($connexion, $req);

if ($resul) {
        # Vérification si une ligne dans la BDD a été trouvée
        if (mysqli_num_rows($resul) == 1) {
                $par_ligne = mysqli_fetch_assoc($resul);
                if (password_verify($motdepasse, $par_ligne['mot_de_passe'])) {
                        #echo "connecté, c'est good";
                        # Si 1, l'utilisateur est connecté, c'est ok
                        $id_utilisateur = $par_ligne['id']; # Récupère l'identifiant de l'utilisateu
                        #echo "id_utilisateur";
                        if (session_status() == PHP_SESSION_NONE) session_start(); # On démarre sa session
                        $_SESSION['utilisateur_id'] = $id_utilisateur; # Stocke l'ID de l'utilisateur dans la session
                        $_SESSION['utilisateur'] = $utilisateur;

                        $_SESSION['status'] = "success";
                        $_SESSION['message'] = "Vous êtes désormais connecté !";
                        header("Location: /trait_profil");
                        #header("Location: /");
                        exit();
                } else {
                        # Mauvais mot de passe
                        if (session_status() == PHP_SESSION_NONE) session_start();
                        $_SESSION['status'] = "danger";
                        $_SESSION['message'] = $error_message;
                        #header("Location: /Connexion");
                }
        } else {
                # Si la variable resul n'est pas de 1, l'utilisateur n'existe pas
                if (session_status() == PHP_SESSION_NONE) session_start();
                $_SESSION['status'] = "danger";
                $_SESSION['message'] = $error_message;
                header("Location: /Connexion");
                #$message = "Vous n'êtes pas connecté, les informations de connexion sont incorrectes";
        }
} else {
        # Erreur de requête
        $message = "Erreur de requête : " . mysqli_error($connexion);
}

#echo $message;
?>
