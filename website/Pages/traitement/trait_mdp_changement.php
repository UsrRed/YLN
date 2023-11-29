<?php
session_start();

if (!isset($_SESSION['utilisateur_id'])) {
        session_start();
        $_SESSION['status'] = "primary";
        $_SESSION['message'] = "Vous devez être connecté, redirection sur la page de connexion...";
        header("Location: /Connexion");
        exit();
}

include('/home/Pages/configBDD/config.php');

# Récup les données du formulaire
$ancienMotDePasse = $_POST['ancienMotDePasse'];
$nouveauMotDePasse = $_POST['nouveauMotDePasse'];
$confirmationMotDePasse = $_POST['confirmationMotDePasse'];

# On s'assure que l'ancien mdp correspond à celui stocké dans la base de données
#$connexion = mysqli_connect($serveur, $utilisateur, $motdepasse, $basededonnees, $port);

$utilisateur = $_SESSION['utilisateur'];

$req = "SELECT * FROM Utilisateur WHERE nom_utilisateur = '$utilisateur'";
$res = mysqli_query($connexion, $req);

if (mysqli_num_rows($res) === 1) { # On vérifie que l'utilisateur existe
        $par_ligne = mysqli_fetch_assoc($res);
        if (password_verify($ancienMotDePasse, $par_ligne['mot_de_passe'])) {
                # Si le mot de passe actuel est correct, on vérifie que le nouveau mot de passe correspond à la confirmation
		if ($nouveauMotDePasse === $confirmationMotDePasse) {
			#Vérif comme pour l'inscription, repris le même pattern 
			
			$message = "";

			function message_evolutif(&$message, $texte){
				if ($message==""){
					$message .= $texte;
				} else {
					$message .= "<br>" . $texte;
				}
			}

			# Filtres mot de passe
			if (8 >= strlen($nouveauMotDePasse) && strlen($nouveauMotDePasse) >= 100){
				message_evolutif($message, "Le mot de passe doit faire entre 8 et 100 caractères");
			}
			# au moins un caractère spécial
			if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $nouveauMotDePasse)){
				message_evolutif($message, "Le mot de passe doit contenir au minimum un caractère spécial");
			}
			# au moins une majuscule
			if (!preg_match('/[A-Z]/', $nouveauMotDePasse)){
				message_evolutif($message, "Le mot de passe doit contenir au minimum une majuscule");
			}
			# au moins une minuscule
			if (!preg_match('/[a-z]/', $nouveauMotDePasse)){
				message_evolutif($message, "Le mot de passe doit contenir au minimum une minuscule");
			}
			# au moins un chiffre
			if (!preg_match('/\d/', $nouveauMotDePasse)){
				message_evolutif($message, "Le mot de passe doit contenir au minimum un chiffre");
			}
			if ($message != ""){
				if (session_status() == PHP_SESSION_NONE) session_start();
				$_SESSION['status'] = "warning";
				$_SESSION['message'] = $message;
				header("Location: /trait_changement_mdp_formulaire");
				exit(1);
			}

                        # hashage et sallage du mot de passe
                        $nouveauMotDePasse = password_hash($nouveauMotDePasse, PASSWORD_DEFAULT);
                        # Si le nouveau mot de passe correspond à la confirmation, on le met à jour dans la base de données
                        $modif_req = "UPDATE Utilisateur SET mot_de_passe = '$nouveauMotDePasse', date_creation_motdepasse = NOW() WHERE nom_utilisateur = '$utilisateur'";
			$modif_res = mysqli_query($connexion, $modif_req);

			$logs = date('Y-m-d H:i:s') . " - [WARNING] - L'utilisateur " . $utilisateur . " vient de changer de mot de passe.";
			shell_exec('echo "' . $logs . '" >> /home/logs/logs.txt');

                        session_destroy(); # On le déconnecte
                        session_start();
                        $_SESSION['status'] = "success";
                        $_SESSION['message'] = "Le mot de passe a été changé avec succès, a prèsent, reconnectez-vous";
                        header("Location: /Connexion");
                } else {
                        if (session_status() == PHP_SESSION_NONE) session_start();
                        $_SESSION['status'] = "warning";
			$_SESSION['message'] = "Le nouveau mot de passe ne correspond pas à la confirmation";

			$logs = date('Y-m-d H:i:s') . " - [WARNING] - L'utilisateur " . $utilisateur . " a tenté de changer de mot de passe.";
			shell_exec('echo "' . $logs . '" >> /home/logs/logs.txt');

                        header("Location: /trait_changement_mdp_formulaire");
                }
        } else {
                if (session_status() == PHP_SESSION_NONE) session_start();
                $_SESSION['status'] = "warning";
		$_SESSION['message'] = "Le mot de passe actuel est incorrect";

		$logs = date('Y-m-d H:i:s') . " - [WARNING] - L'utilisateur " . $utilisateur . " a tenté de changer de mot de passe.";
		shell_exec('echo "' . $logs . '" >> /home/logs/logs.txt');

                header("Location: /trait_changement_mdp_formulaire");
        }
} else {
        # Si ça va là, l'utilisateur n'existe pas
        if (session_status() == PHP_SESSION_NONE) session_start();
        $_SESSION['status'] = "danger";
        $_SESSION['message'] = "L'utilisateur n'existe pas";
        header("Location: /trait_changement_mdp_formulaire");
}
mysqli_close($connexion);
?>
