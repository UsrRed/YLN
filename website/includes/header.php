<!--Page d'header pour chaque page sur notre site WEB. Grace à un include de ce fichier sur nos pages, cela nous permet d'avoir automatiquement le menu déroulant et donc d'alléger le code dans nos fichiers -->

<!DOCTYPE html>
<html>
<head>
    <title>SAE501-502-THEOTIME-MARTEL</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Pour avoir bootstrap version 4.5.2 : https://getbootstrap.com/docs/4.5/getting-started/introduction/-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!--Script dont nous ne sommes pas l'auteur pour avoir l'heure-->

    <script>

        function updateClock() {
            var now = new Date();
            var hours = now.getHours().toString().padStart(2, '0');
            var minutes = now.getMinutes().toString().padStart(2, '0');
            var seconds = now.getSeconds().toString().padStart(2, '0');
            var time = hours + ':' + minutes + ':' + seconds;

            document.getElementById("current-time").textContent = time;
        }

        /*Pour mettre à jour toutes les secondes*/

        setInterval(updateClock, 1000);

    </script>

</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
    <div class="container">
        <a class="navbar-brand" href="#">SAÉ 501-502 - <span id="current-time"></span><a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <!--Pour avoir le menu avec les trois bars lorsque l'écran devient petit, permet d'avoir du responsive avec le menu déroulant -->
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!--Page de notre application sous forme de menu déroulant -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="/">Comparaison</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/Historique">Historique</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/Inscription">Inscription</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/Connexion">Connexion</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/Favoris">Favoris</a>
                        </li>
                        <li>
                            <a class="nav-link" href="/Paramètres">Paramètres</a>
                        </li>
                        <li>
                            <a class="nav-link" href="/trait_faq">FAQ</a>
                        </li>
                    </ul>
                </div>
                <!--On regarde si l'utilisateur est connecté ou non pour l'afficher dans la navbar -->
                <div class="text-right">
			<span class="navbar-text">
			<?php
            if (session_status() == PHP_SESSION_NONE) {
                    session_start();
            }
            if (isset($_SESSION['utilisateur_id'])) {
                    echo '<span class="text-success"><b> &ensp;&ensp; Connecté</b></span>';
            } else {
                    echo '<span class="text-danger"><b> &ensp;&ensp; Déconnecté</b></span>';
            }
            function afficher_etat()
            {
                    if (isset($_SESSION['status']) && isset($_SESSION['message'])) {
                            echo "<div class=\"alert alert-" . $_SESSION['status'] . "\" role=\"alert\">";
                            echo $_SESSION['message'];
                            echo "</div>";
                            unset($_SESSION['status']);
                            unset($_SESSION['message']);
                    }
            }

            ?>
			</span>
                </div>
    </div>
</nav>
