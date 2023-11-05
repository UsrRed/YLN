<?php
session_start();

if (!isset($_SESSION['utilisateur_id'])) {
	header("Location: /Connexion");
	exit();
}

include('/home/Pages/configBDD/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {


	$utilisateur_id = $_SESSION['utilisateur_id'];
	$objet = $_POST['objet']; 	
	$body = $_POST['body'];	
		
	#J'utilise mysqli_real_escape_string car quand je mettais un ' ca changeait de colonne, bizarre, a creuser

	$utilisateur_id = mysqli_real_escape_string($connexion, $utilisateur_id);
	$objet = mysqli_real_escape_string($connexion, $objet);
	$body = mysqli_real_escape_string($connexion, $body);


	# Requête SQL pour récupérer l'adresse e-mail et le mot de passe d'application
	$req = "SELECT adresse_email, mot_de_passe_application FROM Utilisateur WHERE id = '$utilisateur_id'";
	$resultat = $connexion->query($req);

	#En soit l'utilisateur est déjà connecté, pas besoin de faire une vérif s'il existe.
	
	$par_ligne = $resultat->fetch_assoc();
	$adresse_email = $par_ligne['adresse_email'];
		
	$destinataire = 'nathan.martel@etu.univ-tours.fr'; 
	
	$commande = "echo \"Subject: $objet\n$body\" | msmtp -a default -t $destinataire";

	#$commande = 'echo "Subject: Sujet de votre e-mail\nCorps de votre e-mail" | msmtp -a default -t nathan.martel@etu.univ-tours.fr';

	#$test_commande = "ls -alh ~ | grep .msmtprc";
	#$res_test = shell_exec($test_commande);
	#echo "Resultat test : $res_test";

	#$test_commande = 'echo "Subject: Sujet de votre e-mail\nCorps de votre e-mail" | msmtp -a default -t nathan.martel@etu.univ-tours.fr';
	#$res_test = shell_exec($test_commande);
	#echo "res : $res_test";

	$resultat_msmtp = shell_exec($commande);
	
	#Trace dans la base de données : 
	
	$insertion = "INSERT INTO FAQ (utilisateur_id, objet, corps, date_submission) VALUES ('$utilisateur_id', '$objet', '$body', NOW())";

	$connexion->query($insertion);


	if (!empty($resultat_msmtp)) {
		#echo "Mail envoyé avec succès.";
		?>
		<script>
			alert("Mail envoyé avec succès ! On vous répondra dans les plus bref délai");
			window.location.href = "/";
		</script>
		<?php
	} else {
		#echo "Erreur lors de l'envoi du mail.";
		?>
		<script>
			alert("Mail envoyé avec succès ! On vous répondra dans les plus bref délai");
			window.location.href = "/trait_support";
		</script>
		<?php
	}

$connexion->close();
}

?>
