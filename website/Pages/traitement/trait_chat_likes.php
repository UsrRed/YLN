<?php
// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() == PHP_SESSION_NONE) session_start();

// Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
if (!isset($_SESSION['utilisateur_id'])) {
        // Définir un message et une couleur de statut pour la redirection
        $_SESSION['status'] = "primary";
        $_SESSION['message'] = "Vous devez être connecté, redirection sur la page de connexion...";

        // Rediriger vers la page de connexion
        header("Location: /Connexion");
        exit();
}

// Inclure le fichier de configuration de la base de données
include('/home/Pages/configBDD/config.php');

// Vérifier si l'action et l'ID du message sont définis dans la requête
if (isset($_POST['action']) && isset($_POST['message_id'])) {
        // Sanitiser les données
        $action = filter_var($_POST['action'], FILTER_UNSAFE_RAW);
        $message_id = filter_var($_POST['message_id'], FILTER_SANITIZE_NUMBER_INT);

        // Vérifier si l'action est valide (like ou dislike)
        if ($action === 'like' || $action === 'dislike') {
                // Mettre à jour les likes ou dislikes dans la base de données
                if ($action === 'like') {
                        $sql = "UPDATE Messages SET like_count = like_count + 1 WHERE message_id = $message_id";
                } else {
                        $sql = "UPDATE Messages SET dislike_count = dislike_count + 1 WHERE message_id = $message_id";
                }
                $connexion->query($sql);
        }
}
// Fermer la connexion à la base de données
$connexion->close();
header("Location: /chat");
?>
