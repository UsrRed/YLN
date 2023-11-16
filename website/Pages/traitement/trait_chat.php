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

include('/home/Pages/configBDD/config.php');

// Récupérer les informations de l'utilisateur connecté
$nom_utilisateur = $_SESSION['utilisateur'];
$id_utilisateur = $_SESSION['utilisateur_id'];

// Traitement de l'envoi d'un message
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Utiliser mysqli_real_escape_string pour échapper les caractères spéciaux
        $message = mysqli_real_escape_string($connexion, $_POST['message']);

        // Insérer le message dans la table des messages (remplacer avec les vrais noms de colonnes)
        $sql = "INSERT INTO Messages (utilisateur_id, texte, date, like_count, dislike_count) VALUES ('$id_utilisateur', '$message', NOW(), 0, 0)";

        // Vérifier si la requête a été exécutée avec succès
        if ($connexion->query($sql) !== TRUE) {
                echo "Erreur : " . $sql . "<br>" . $connexion->error;
        }
}

// Fermer la connexion à la base de données
$connexion->close();

header("Location: /chat");
?>
