<?php
if (session_status() == PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['utilisateur_id'])) {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $_SESSION['status'] = "primary";
        $_SESSION['message'] = "Vous devez être connecté, redirection sur la page de connexion...";
        header("Location: /Connexion");
        exit();
}

include('/home/Pages/configBDD/config.php');

$utilisateur = $_SESSION['utilisateur'];
$age = filter_var($_POST['age'], FILTER_SANITIZE_NUMBER_INT);
$age = htmlspecialchars($age);
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$app_password = $_POST['app_password'];
$utilisateur_id = $_SESSION['utilisateur_id']; #ID de l'utilisateur connecté

if ($age>0 && $age<125) {
        #Met à jour les données de l'utilisateur
        $update_query = "UPDATE Utilisateur SET age = '$age', adresse_email = '$email', mot_de_passe_application = '$app_password' WHERE id = '$utilisateur_id'";

        #Exécute la requête
        if ($connexion->query($update_query) === TRUE) {
                #echo "Données mises à jour avec succès.";
                if (session_status() == PHP_SESSION_NONE) session_start();
                $_SESSION['status'] = "success";
		$_SESSION['message'] = "Les données ont été mises à jour avec succès";

		$logs = date('Y-m-d H:i:s') . " - [INFO] - L'utilisateur " . $utilisateur . " a remplit les informations de son profil.";
		shell_exec('echo "' . $logs . '" >> /home/logs/logs.txt');

                header("Location: /Paramètres");
        } else {
                #echo "Erreur lors de la mise à jour des données : " . $connexion->error;
                if (session_status() == PHP_SESSION_NONE) session_start();
                $_SESSION['status'] = "danger";
                $_SESSION['message'] = "Erreur lors de la mise à jour des données";
                header("Location: /trait_profil");
        }

        // Fermez la connexion à la base de données
        $connexion->close();
} else {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $_SESSION['status'] = "warning";
        $_SESSION['message'] = "Merci de saisir un âge correct";
        header("Location: /trait_profil");
}
?>

