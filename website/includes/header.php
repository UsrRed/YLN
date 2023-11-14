<!--Page d'header pour chaque page sur notre site WEB. Grace à un include de ce fichier sur nos pages, cela nous permet d'avoir automatiquement le menu déroulant et donc d'alléger le code dans nos fichiers -->

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
        <a class="navbar-brand" href="#"><img src="logo.jpg" width="20" height="20"/>&ensp;SAÉ 501-502 - <span id="current-time"></span><a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <!--Pour avoir le menu avec les trois bars lorsque l'écran devient petit, permet d'avoir du responsive avec le menu déroulant -->
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!--Page de notre application sous forme de menu déroulant -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item mx-1">
                            <a class="nav-link" href="/accueil">Comparaison</a>
                        </li>
                        <li class="nav-item mx-1">
                            <a class="nav-link" href="/Historique">Historique</a>
                        </li>
                        <li class="nav-item mx-1">
                            <a class="nav-link" href="/Favoris">Favoris</a>
                        </li>
                        <li class="nav-item mx-1">
                            <a class="nav-link" href="/Paramètres">Paramètres</a>
                        </li>
                        <?php
                        if (session_status() == PHP_SESSION_NONE) session_start();
                        $nom_utilisateur = $_SESSION['utilisateur'];
                        if ($nom_utilisateur == 'admin') {
                            # N'affiche la vue globale qu'aux administrateurs
                        ?>
                            <li>
                                <a class="nav-link" href="/Vue_globale">Vue globale</a>
                            </li>
                        <?
                        }
                        ?>
                        <li>
                            <a class="nav-link" href="/trait_faq">FAQ</a>
                        </li>
                        <?php if (!isset($_SESSION['utilisateur_id'])) { # Si l'utilisateur n'est pas connecté ?>
                            <li class="nav-item mx-1">
                                <a class="nav-link btn btn-success" style="color: white;" href="/Inscription">Inscription</a>
                            </li>
                            <li class="nav-item mx-1">
                                <a class="nav-link btn btn-success" style="color: white;" href="/Connexion">Connexion</a>
                            </li>
                        <?php } else { ?>
                            <li class="nav-item mx-1">
                                <a class="nav-link btn btn-outline-danger" style="border: unset;" href="/trait_deconnexion">Déconnexion</a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
                <!--On regarde si l'utilisateur est connecté ou non pour l'afficher dans la navbar -->
                <div class="text-right">
			<span class="navbar-text">
			<?php
            if (session_status() == PHP_SESSION_NONE) session_start();

            if (isset($_SESSION['utilisateur_id'])) {
                    echo '<span class="text-success"><b> &ensp;&ensp; ' . $_SESSION['utilisateur'] . '</b></span>';
            } else {
                    echo '<span class="text-danger"><b> &ensp;&ensp; Déconnecté</b></span>';
            }
            function afficher_etat()
            {
                    if (isset($_SESSION['status']) && isset($_SESSION['message'])) {
                            echo "<div class=\"alert alert-" . $_SESSION['status'] . "\" role=\"alert\">";
                            echo $_SESSION['message'];
                            echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">";
                            echo "    <span aria-hidden=\"true\">&times;</span>";
                            echo "</button>";
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
