<?php
if (session_status() == PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['utilisateur_id'])) {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $_SESSION['status'] = "primary";
        $_SESSION['message'] = "Vous devez être connecté, redirection sur la page de connexion...";
        header("Location: /Connexion");
        exit();
}

$utilisateur = $_SESSION['utilisateur'];

include('/home/Pages/configBDD/config.php');

if (isset($_POST['ajouter_favoris'])) {
        $id_utilisateur = $_SESSION['utilisateur_id'];
        $comparaison_id = $_POST['comparaison_id'];
	$comparaison_id = filter_var($_POST['comparaison_id'], FILTER_SANITIZE_NUMBER_INT);
	$comparaison_id = htmlspecialchars($comparaison_id);
        #echo $id_utilisateur;
        #echo "";
        #echo $comparaison_id;
        #Pour avoir la comparaison dans la table des favris

        $req_favo = "INSERT INTO Favoris (utilisateur_id, historique_id, date_favoris) VALUES ('$id_utilisateur', '$comparaison_id', NOW())";
	mysqli_query($connexion, $req_favo);

	$utilisateur = $_SESSION['utilisateur'];
	$logs = date('Y-m-d H:i:s') . " - [INFO] - L'utilisateur " . $utilisateur . " vient de mettre en favoris la comparaison de $comparaison1 et $comparaison2.";
	shell_exec('echo "' . $logs . '" >> /home/logs/logs.txt');
	
	#Et on redirige vers une page favoris
        if (session_status() == PHP_SESSION_NONE) session_start();
        $_SESSION['status'] = "success";
        $_SESSION['message'] = "Ajout du favoris avec succès";
        header("Location: /Favoris");
        exit();
}
?>
