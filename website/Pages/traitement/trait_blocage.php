<?#php include('/home/includes/header.php'); ?>
<!DOCTYPE html>

<html>
<head>
	<title>SAE501-502-THEOTIME-MARTEL</title>
	<meta charset="utf-8">
	<link rel="icon" type="image/png" href="logo.jpg" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<!-- Pour avoir bootstrap version 4.5.2 : https://getbootstrap.com/docs/4.5/getting-started/introduction/-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

	<style>
        	#countdown {
			font-size: 300px;
		}
	</style>

</head>

<body class="bg-light" disabled> <!--Pour que l'utilisateur ne puisse rien faire -->
<div class="container mt-5 text-center"> <!-- Ajout de la classe text-center pour centrer le contenu -->
    
<br><br><br><h2>Votre compte est temporairement <b>bloqué</b>. Vous serez redirigé vers la page de connexion à la fin du compte a rebours</h2><br/><br/><br/><br/><br/><br/><br/>
	<h1><span id="countdown" class="display-1">300</span></h1> <!-- Ajout de la classe display-1 pour le texte en très gros -->

	<script>

	/* Fonctions dont nous ne sommes pas l'auteur*/

	var seconds;
	function startCountdown() {
		if (localStorage.getItem("countdownSeconds")) {
			seconds = parseInt(localStorage.getItem("countdownSeconds"), 10);
		} else {
			seconds = 300; // Changer la valeur initiale à 300
			localStorage.setItem("countdownSeconds", seconds.toString()); // Ajoutez cette ligne pour stocker la valeur initiale
		}

 	function updateCountdown() {
		var minutes = Math.floor(seconds / 60);
		var remainingSeconds = seconds % 60;

		remainingSeconds = remainingSeconds < 10 ? "0" + remainingSeconds : remainingSeconds;

		document.getElementById("countdown").textContent = minutes + ":" + remainingSeconds;

		if (seconds <= 0) {
			window.location.replace("/Connexion");
		} else {
			seconds--;
			localStorage.setItem("countdownSeconds", seconds.toString());
			setTimeout(updateCountdown, 1000);
			}
		}
		updateCountdown();
	}
	startCountdown();

	</script>
</div>
</body>
</html>
