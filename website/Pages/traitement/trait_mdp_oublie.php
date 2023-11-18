<?php
#Connexion à la base de données :

include('/home/Pages/configBDD/config.php');

#$commandeIPSite = 'podman inspect haproxy | grep -oP \'"IPAddress": "\K[^\"]+\'';
#$IP = shell_exec($commandeIPSite);
#Normal que ça ne fonctionne pas, c'est le conteneur qui exécute la commande, pas le pc hôte !
#--> Essayer de voir comment récupérer l'adresse IP du conteneur haproxy

# Pour récupérer les données du formulaire

if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $utilisateur = $_POST["utilisateur"];
	$utilisateur = filter_var($utilisateur, FILTER_UNSAFE_RAW);
	$utilisateur = htmlspecialchars($utilisateur);
        $email = $_POST["email"];
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $age = $_POST["age"];
	$age = filter_var($age, FILTER_SANITIZE_NUMBER_INT);
	$age = htmlspecialchars($age);
        if ($age<0 || $age>125){
                if (session_status() == PHP_SESSION_NONE) session_start();
                $_SESSION['status'] = "warning";
                $_SESSION['message'] = "Merci de saisir un âge valide";
                header("Location: /trait_mdp_oublie_formulaire");
        }
        $motdepasse = $_POST["motdepasse"];
        $motdepasse = filter_var($motdepasse, FILTER_UNSAFE_RAW);

        #On vérif les données du formulaire en fonction de celles dans la base de données. On se sert des données du formulaire et son fait une requête avec pour voir s'il y a des lignes qui ressortent dans la base de données

        $req = "SELECT * FROM Utilisateur WHERE nom_utilisateur = ? AND adresse_email = ? AND age = ? AND mot_de_passe_application = ?";

        $stmt = $connexion->prepare($req);
        $stmt->bind_param("ssis", $utilisateur, $email, $age, $motdepasse); #String, String, entier (integer), String
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {

		$nouv_mdp = mdp_aleatoire();
		$nouv_mdp_hash = password_hash($nouv_mdp, PASSWORD_DEFAULT);

                #Si les données correspondent, on lance un processus de mail
                require '/usr/share/nginx/composer/vendor/autoload.php';

                $req1 = "SELECT nom_utilisateur, adresse_email, mot_de_passe_application FROM Utilisateur WHERE nom_utilisateur = '$utilisateur'";
                $resultat = $connexion->query($req1);
                $par_ligne = $resultat->fetch_assoc();
                $vrai_adresse_email = $par_ligne['adresse_email'];
                $vrai_mot_de_passe_application = $par_ligne['mot_de_passe_application'];
                $vrai_nom_utilisateur = $par_ligne['nom_utilisateur'];

                #On met a jour le mot de passe aléatoire dans la base de données : 

		$modif_Req = "UPDATE Utilisateur SET mot_de_passe = ?, date_creation_motdepasse = DATE_SUB(NOW(), INTERVAL 2 DAY) WHERE nom_utilisateur = ?"; #Pour qu'il soit déjà expiré
		$modif_Stmt = $connexion->prepare($modif_Req);
                $modif_Stmt->bind_param("ss", $nouv_mdp_hash, $utilisateur);
                $modif_Stmt->execute();
                $modif_Stmt->close();


                $mail = new PHPMailer\PHPMailer\PHPMailer();

                #Compte gmail sae501502gmail.com créé pour l'envoi de mail à l'utilisateur qui a oublié son mot de passe

                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'sae501502@gmail.com'; #Adresse e-mail gmail pour l'envoi
                $mail->Password = 'xqifxpjrieknuntn'; #Mot de passe d'application
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('sae501502@gmail.com', 'SAE501-502 - mot de passe');
                $mail->addAddress($vrai_adresse_email); #adresse e-mail récupérée depuis la base de données. Le destinataire

                $mail->isHTML(false);
                $mail->Subject = 'Oublie du mot de passe';
                $mail->Body = "Bonjour $utilisateur,\n\nVous avez oublié votre mot de passe, en voici un nouveau pour votre compte : $nouv_mdp\nCe mot de passe est déjà expiré. Rendez-vous sur la page de connexion et vous serez invité à modifier votre mot de passe.\n\nCordialement,";

                #Pour envoyer l'e-mail

                if (!$mail->send()) {
                        if (session_status() == PHP_SESSION_NONE) session_start();
                        $_SESSION['status'] = "danger";
                        $_SESSION['message'] = "Erreur lors de l'envoi de l'e-mail";
                        header("Location: /trait_mdp_oublie_formulaire");
                } else {
                        if (session_status() == PHP_SESSION_NONE) session_start();
                        $_SESSION['status'] = "success";
                        $_SESSION['message'] = "E-mail envoyé avec succès, consulez vos e-mails";
                        header("Location: /Connexion");
                }

        } else {
                #echo "Les données ne correspondent pas. Veuillez vérifier vos informations.";
                if (session_status() == PHP_SESSION_NONE) session_start();
                $_SESSION['status'] = "success";
                $_SESSION['message'] = "Les données ne correspondent pas. Veuillez vérifier vos informations.";
                header("Location: /trait_mdp_oublie_formulaire");
        }

        $stmt->close();
}

$connexion->close();

function mdp_aleatoire($long = 24)
{

        $carac = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!?,#&@*';
        $mdp = '';
        for ($i = 0; $i < $long; $i++) {
                $mdp .= $carac[rand(0, strlen($carac) - 1)]; #On se sert des indices d'ou le moins 1, c'est pour ca que ca ne fonctionnait pas depuis le début
        }
        return $mdp;
}


?>

