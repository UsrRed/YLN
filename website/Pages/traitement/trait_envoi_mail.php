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

require '/usr/share/nginx/composer/vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $utilisateur_id = $_SESSION['utilisateur_id'];
        $objet = $_POST['objet'];
	$objet = filter_var($objet, FILTER_UNSAFE_RAW);
	$objet = htmlspecialchars($objet);
        $body = $_POST['body'];
        $body = filter_var($body, FILTER_UNSAFE_RAW);
	$body = htmlspecialchars($body);

        #J'utilise mysqli_real_escape_string car quand je mettais un ' ca changeait de colonne, bizarre, a creuser

        $utilisateur_id = mysqli_real_escape_string($connexion, $utilisateur_id);
        $objet = mysqli_real_escape_string($connexion, $objet);
        $body = mysqli_real_escape_string($connexion, $body);

        #On récup l'adresse mail et le mot_de_passe d'application de l'utilisateur actuel afin de permettre l'envoi du mail

        $req = "SELECT nom_utilisateur, adresse_email, mot_de_passe_application FROM Utilisateur WHERE id = '$utilisateur_id'";
        $resultat = $connexion->query($req);
        $par_ligne = $resultat->fetch_assoc();
        $adresse_email = $par_ligne['adresse_email'];
        $mot_de_passe_application = $par_ligne['mot_de_passe_application'];
        $nom_utilisateur = $par_ligne['nom_utilisateur'];

	$destinataire1 = getenv('EMAIL1');
	$destinataire2 = getenv('EMAIL2');
	$destinataire3 = getenv('EMAIL3');
	#$destinataire1 = 'nathan.martel@etu.univ-tours.fr';
        #$destinataire2 = 'lukas.theotime@etu.univ-tours.fr';
        #$destinataire3 = 'yohann.denoyelle@etu.univ-tours.fr';

        #Fichier de base de PHPMailer, https://github.com/PHPMailer/PHPMailer

        $mail = new PHPMailer\PHPMailer\PHPMailer();

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $adresse_email;
        $mail->Password = $mot_de_passe_application;
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom($adresse_email, $nom_utilisateur);
        $mail->addAddress($destinataire1);
        $mail->addAddress($destinataire2);
        $mail->addAddress($destinataire3);

        $mail->isHTML(false);
        $mail->Subject = $objet;
        $mail->Body = $body;


        if ($mail->send()) {
                if (session_status() == PHP_SESSION_NONE) session_start();
                $_SESSION['status'] = "success";
		$_SESSION['message'] = "Mail envoyé avec succès ! On vous répondra dans les plus brefs délais";

		$logs = date('Y-m-d H:i:s') . " - [INFO] - L'utilisateur " . $nom_utilisateur . " vient de faire une demande au support.";
		shell_exec('echo "' . $logs . '" >> /home/logs/logs.txt');

                header("Location: /accueil");
        } else {
                if (session_status() == PHP_SESSION_NONE) session_start();
                $_SESSION['status'] = "danger";
                $_SESSION['message'] = "Erreur lors de l'envoi du mail.";
                header("Location: /trait_support");
        }

        #Trace dans la base de données :


        $insertion = "INSERT INTO FAQ (utilisateur_id, objet, corps, date_submission) VALUES ('$utilisateur_id', '$objet', '$body', NOW())";
        $connexion->query($insertion);


        $connexion->close();
}
?>
