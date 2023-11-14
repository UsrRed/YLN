<?php #include('/home/includes/header.php'); ?>

<body class="bg-light" disabled>
<div class="container mt-5 text-center"> <!-- Ajout de la classe text-center pour centrer le contenu -->

    <h2>Votre compte est temporairement bloqué</h2><br/><br/>
    <p><span id="countdown" class="display-1">300</span></p> <!-- Ajout de la classe display-1 pour le texte en très gros -->

    <script>
        // Compteur à rebours en JavaScript
        var seconds = 300; // 5 minutes
        function countdown() {
            var minutes = Math.floor(seconds / 60);
            var remainingSeconds = seconds % 60;

            // Ajoute un zéro devant les chiffres de moins de 10
            remainingSeconds = remainingSeconds < 10 ? "0" + remainingSeconds : remainingSeconds;

            document.getElementById("countdown").textContent = minutes + ":" + remainingSeconds;

            if (seconds <= 0) {
                window.location.replace("/Connexion"); // On redirige après le compte à rebours
            } else {
                seconds--;
                setTimeout(countdown, 1000);
            }
        }
        countdown();
    </script>
</div>
</body>
</html>
