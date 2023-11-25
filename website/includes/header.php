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
  

    <style><?php include("/home/includes/CSS/chat.css"); ?></style> 
    

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
<body id="theme-body" class="bg-light">
<nav class="navbar navbar-expand-xl navbar-dark bg-secondary">

<button id="toggle-btn" class="themeLogo">&#9789;</button>

    <div class="container">

<a class="navbar-brand" href="#">
      &nbsp;<img src="logo.jpg" width="20" height="20"/>&ensp;SAÉ 501-502 - <span id="current-time">00:00:00</span>
</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <!--Pour avoir le menu avec les trois bars lorsque l'écran devient petit, permet d'avoir du responsive avec le menu déroulant -->
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!--Page de notre application sous forme de menu déroulant -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item m-1">
                            <a class="nav-link" style="white-space: nowrap" href="/accueil">Comparaison</a>
                        </li>
                        <li class="nav-item m-1">
                            <a class="nav-link" style="white-space: nowrap" href="/chat">Chat</a>
                        </li>
                        <li class="nav-item m-1">
                            <a class="nav-link" style="white-space: nowrap" href="/Historique">Historique</a>
                        </li>
                        <li class="nav-item m-1">
                            <a class="nav-link" style="white-space: nowrap" href="/Favoris">Favoris</a>
                        </li>
                        <li class="nav-item m-1">
                            <a class="nav-link" style="white-space: nowrap" href="/Paramètres">Paramètres</a>
                        </li>
                        <?php
                        if (session_status() == PHP_SESSION_NONE) session_start();
                        if (isset($_SESSION['utilisateur'])) {
                            $nom_utilisateur = $_SESSION['utilisateur'];
                            if ($nom_utilisateur == 'admin') {
                                # N'affiche la vue globale qu'aux administrateurs
                            ?>
                                <li>
                                    <a class="nav-link m-1" style="white-space: nowrap" href="/Vue_globale">Vue globale</a>
                                </li>
                            <?php
                            }
                        }
                        ?>
                        <li>
                            <a class="nav-link m-1" style="white-space: nowrap;" href="/trait_faq">FAQ</a>
                        </li>
                        <?php if (!isset($_SESSION['utilisateur_id'])) { # Si l'utilisateur n'est pas connecté ?>
                            <li class="nav-item m-1">
                                <a class="nav-link btn btn-outline-success text-left" style="border: unset; white-space: nowrap;" href="/Inscription">Inscription</a>
                            </li>
                            <li class="nav-item m-1">
                                <a class="nav-link btn btn-outline-success text-left" style="border: unset; white-space: nowrap;" href="/Connexion">Connexion</a>
                            </li>
                        <?php } else { ?>
                            <li class="nav-item m-1">
                                <a class="nav-link btn btn-outline-danger text-left" style="border: unset; white-space: nowrap;" href="/trait_deconnexion">Déconnexion</a>
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
                    echo '<span class="text-success m-1" style="white-space: nowrap" ><b>' . $_SESSION['utilisateur'] . ' est connecté(e)</b></span>';
            } else {
                    echo '<span class="text-danger m-1" style="white-space: nowrap" ><b>Déconnecté</b></span>';
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Attend que le document soit chargé

        var themeButton = document.getElementById('toggle-btn');

        // Ajoute un écouteur d'événements pour le clic sur le bouton
        themeButton.addEventListener('click', function () {
            // Bascule entre les classes 'bg-light' et 'bg-dark' du body
            document.body.classList.toggle('bg-light');
            document.body.classList.toggle('bg-dark');

            // Bascule entre les classes 'text-dark' et 'text-light' pour le texte
            document.body.classList.toggle('text-dark');
            document.body.classList.toggle('text-light');

            // Stocke l'état du thème dans un cookie
            var themeState = document.body.classList.contains('bg-dark') ? 'dark' : 'light';
            document.cookie = 'theme=' + themeState + '; path=/';
        });

        // Récupère l'état du thème depuis le cookie lors du chargement de la page
        var themeCookie = document.cookie.replace(/(?:(?:^|.*;\s*)theme\s*=\s*([^;]*).*$)|^.*$/, "$1");
        if (themeCookie === 'dark') {
            // Applique la classe 'bg-dark' au body si le thème sombre est enregistré
            document.body.classList.add('bg-dark', 'text-light');
        }
    });
</script>
