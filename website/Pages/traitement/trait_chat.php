<?php
if (session_status() == PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['utilisateur_id'])) {
        $_SESSION['status'] = "primary";
        $_SESSION['message'] = "Vous devez être connecté, redirection sur la page de connexion...";
        header("Location: /Connexion");
        exit();
}

include('/home/Pages/configBDD/config.php');

$nom_utilisateur = $_SESSION['utilisateur'];
$id_utilisateur = $_SESSION['utilisateur_id'];

# envoi d'un message
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	# enlever les caractères spéciaux
	$mess_sans_verif = filter_var($_POST['message'], FILTER_UNSAFE_RAW);
	$mess = htmlspecialchars($mess_sans_verif);
        $message = mysqli_real_escape_string($connexion, $mess);
        $sql = "INSERT INTO Messages (utilisateur_id, texte, date, like_count, dislike_count) VALUES ('$id_utilisateur', '$message', NOW(), 0, 0)";

        # Vérifie la requête
        if ($connexion->query($sql) !== TRUE) {
                echo "Erreur : " . $sql . "<br>" . $connexion->error;
        }
}
$connexion->close();

header("Location: /chat");
?>
