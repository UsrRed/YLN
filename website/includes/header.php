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
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">SAÉ 501-502</a>
       <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"> <!--Pour avoir le menu avec les trois bars lorsque l'écran devient petit, permet d'avoir du responsive avec le menu déroulant -->
            <span class="navbar-toggler-icon"></span>
	</button>
	<!--Page de notre application sous forme de menu déroulant -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/index.php">Comparaison</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/Pages/Historique">Historique</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/Pages/Inscription.php">Inscription</a>
		</li>
		<li>
		    <a class="nav-link" href="/Pages/Connexion.php">Connexion</a>
		</li>
		<li>
		    <a class="nav-link" href="/Pages/Favoris.php">Favoris</a>
		</li>
            </ul>
        </div>
    </div>
</nav>
