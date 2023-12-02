<?php
# Connexion à la base de données :

$error_message = "Mauvais nom d'utilisateur ou mot de passe !";

#Pour envoi de mail et connexion vers la base de données

include('/home/Pages/configBDD/config.php');
require '/usr/share/nginx/composer/vendor/autoload.php';

# Pour récupérer les données du formulaire
$utilisateur = filter_var($_POST['utilisateur'], FILTER_UNSAFE_RAW);
$utilisateur = htmlspecialchars($utilisateur);
$motdepasse = $_POST['motdepasse'];
#$message = "";

# Vérification si l'utilisateur existe bien
$req = "SELECT * FROM Utilisateur WHERE nom_utilisateur='$utilisateur'";

$resul = mysqli_query($connexion, $req);

if ($resul) {
	# Vérification si une ligne dans la BDD a été trouvée
	if (mysqli_num_rows($resul) == 1) {
		$par_ligne = mysqli_fetch_assoc($resul);

		$date_creation_motdepasse = strtotime($par_ligne['date_creation_motdepasse']);
		$expiration_motdepasse = $date_creation_motdepasse + (2 * 24 * 60 * 60); # 2jours (en secondes) pour le temps d'expiration de mot de passe.
		#$expiration_motdepasse = $date_creation_motdepasse + (60);

		if (password_verify($motdepasse, $par_ligne['mot_de_passe']) && time() > $expiration_motdepasse) {
			if (session_status() == PHP_SESSION_NONE) session_start();
			$_SESSION['status'] = "danger";
			$_SESSION['message'] = "Mot de passe expiré. Veuillez réinitialiser votre mot de passe.";
			$_SESSION['utilisateur_id'] = $par_ligne['id'];
			$_SESSION['utilisateur'] = $par_ligne['nom_utilisateur'];

			$logs = date('Y-m-d H:i:s') . " - [INFO] - Le mot de pase de l'utilisateur " . $utilisateur . " vient d'expirer.";
			shell_exec('echo "' . $logs . '" >> /home/logs/logs.txt');

			header("Location: /trait_reinitialisation_mdp_formulaire");
			exit();
			}
		
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

			# On refixe à 0 s'il s'est connecté
			$majten = "UPDATE Utilisateur SET tentatives_echouees=0, temps_blocage=0 WHERE nom_utilisateur='$utilisateur'";
			mysqli_query($connexion, $majten);

			#shell_exec("echo 'L\'utilisateur $utilisateur vient de se connecter' >> /home/Pages/test.txt");
			#$output = shell_exec("test variable");

			$logs = date('Y-m-d H:i:s') . " - [INFO] - L'utilisateur " . $utilisateur . " s'est connecté.";
			shell_exec('echo "' . $logs . '" >> /home/logs/logs.txt');
			
			#shell_exec('echo "" >> /home/logs/logs.txt');

			header("Location: /trait_profil");
			#header("Location: /");
			exit();
		} else {
			#Mauvais mot de passe

			$logs = date('Y-m-d H:i:s') . " - [WARNING] - L'utilisateur " . $utilisateur . " a tenté de se connecter.";
			shell_exec('echo "' . $logs . '" >> /home/logs/logs.txt');

			if ($par_ligne['tentatives_echouees'] < 2) {
				# On incrémente le compteur de tentatives de connexion échouées
				$tentatives_echouees = $par_ligne['tentatives_echouees'] + 1;
				$tentatives_restantes = 3 - $tentatives_echouees;

				$temps_blocage = time() + (5 * 60); # Blocage pendant 5 minutes
    				$req_update = "UPDATE Utilisateur SET tentatives_echouees=$tentatives_echouees, temps_blocage=$temps_blocage WHERE nom_utilisateur='$utilisateur'";
				mysqli_query($connexion, $req_update);

				#Là, on vérifie le temps de blocage
				if ($par_ligne['temps_blocage'] > 0) {
					$temps_restant = $par_ligne['temps_blocage'] - time();
					if ($temps_restant <= 0) {
						#A cet instant, le temps de blocage est écoulé donc on réinitialise le compteur et le temps de blocage pour de nouveau avoir accès au compte
						$majten = "UPDATE Utilisateur SET tentatives_echouees=0, temps_blocage=0 WHERE nom_utilisateur='$utilisateur'"; ###########ESSAYER DE VOIR COMMENT LE FAIRE DEPUIS LA TABLE COMPTEUR
						mysqli_query($connexion, $majten);
					} else {
						#Go afficher un message avec le nombre de tentatives restantes
						if (session_status() == PHP_SESSION_NONE) session_start();
						$_SESSION['status'] = "danger";
						$_SESSION['message'] = "Mauvais nom d'utilisateur ou mot de passe ! Il vous reste $tentatives_restantes tentatives avant de vous faire bannir."; #Réessayez dans $temps_restant secondes.";
						header("Location: /Connexion");
						exit();
					}
 				}

				#Message d'erreur avec le nombre de tentatives restantes
				if (session_status() == PHP_SESSION_NONE) session_start();
				$_SESSION['status'] = "danger";
				$_SESSION['message'] = "Mauvais nom d'utilisateur ou mot de passe ! Il vous reste $tentatives_restantes tentatives avant de vous faire bannir.";
				header("Location: /Connexion");
			} else {
				#On vérifie si le temps de blocage a expiré après 5 minutes
				if ($par_ligne['temps_blocage'] > 0) {
					$temps_restant = $par_ligne['temps_blocage'] - time();
					if ($temps_restant <= 0) {
						#Là, le temps de blocage est écoulé, donc on réinitialise
						$majten = "UPDATE Utilisateur SET tentatives_echouees=0, temps_blocage=0 WHERE nom_utilisateur='$utilisateur'";
						mysqli_query($connexion, $majten);
					} else {
						#Message de blocage temporaire avec le temps restant
						if (session_status() == PHP_SESSION_NONE) session_start();
						$_SESSION['status'] = "danger";
						$_SESSION['message'] = "Compte temporairement bloqué. Réessayez dans $temps_restant secondes. Un mail a été envoyé !";

						$logs = date('Y-m-d H:i:s') . " - [CRITICAL] - L'utilisateur " . $utilisateur . " vient de se faire bannir.";
						shell_exec('echo "' . $logs . '" >> /home/logs/logs.txt');

						$mail = new PHPMailer\PHPMailer\PHPMailer();
						$mail->isSMTP();
				                $mail->Host = 'smtp.gmail.com';
             	   				$mail->SMTPAuth = true;
              	  				$mail->Username = 'sae501502@gmail.com'; #Adresse e-mail gmail pour l'envoi
           	     				$mail->Password = 'xqifxpjrieknuntn'; #Mot de passe d'application
               	 				$mail->SMTPSecure = 'tls';
             					$mail->Port = 587;
						$mail->setFrom('sae501502@gmail.com', 'SAE501-502 - bannissement');
						$mail->addAddress('nathan.martel@etu.univ-tours.fr');
						$mail->addAddress('lukas.theotime@etu.univ-tours.fr');
						$mail->addAddress('yohann.denoyelle@etu.univ-tours.fr');
						$mail->isHTML(false);
   	        				$mail->Subject = "[WARNING] - SAE501-502";
       	         				$mail->Body = "L'utilisateur $utilisateur vient de se faire bannir 5 minutes sur l'application suite à trois tentatives de connexions infructueuses.";
						$mail->send();

						#header("Location: /Connexion");
						header("Location: /trait_blocage");
						exit();
					}
 				} else {
					#Message de blocage temporaire avec le temps restant
 					if (session_status() == PHP_SESSION_NONE) session_start();
					$_SESSION['status'] = "danger";
					$_SESSION['message'] = "Compte temporairement bloqué. Réessayez dans $temps_restant secondes.";
						
					#header("Location: /Connexion");
					header("Location: /trait_blocage");
					exit();
 				}
			}
		}
	} else {
		#L'utilisateur n'existe pas
		if (session_status() == PHP_SESSION_NONE) session_start();
		$_SESSION['status'] = "danger";
		$_SESSION['message'] = "Utilisateur introuvable, inscrivez-vous d'abord";
		header("Location: /Inscription");
	}
} else {
		$message = "Erreur de requête : " . mysqli_error($connexion);
}

#echo $message;
?>
