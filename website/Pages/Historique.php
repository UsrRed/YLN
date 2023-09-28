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
<?php include('/home/includes/header.php'); ?>
<div class="container mt-5">

Page pour l'historique des comparaisons en fonction de l'utilisateur
<br/><br/>
Idée : 

1) Supprimer la variable utilisateur_id temporaire pour que le champ utilisateur_id dans Historique soit l'id de l'utilisateur et pas 1.
<br/>
2) Faire un session_start et pas !isset de session[utilisateur] pour savoir si l'utilisateur est co
<br/>
3) Il faut récup l'id de l'utilisateur donc d'abord on récupère son nom avec le fomulaire qu'on stocke dans une variable et ensuite on fait un SELECT id FROM utilisateur WHERE nom_utilisateur = [VariableNomUtilisateur]; 
<br/>
4) Normalement on a l'id et ensuite il manque plus qu'a faire un select * de l'historique avec un where de l'id récupéré
<br/>
5) APrès pour afficher faut une boucle je pense mais comment faire ? regarder ça : mysqli_fetch_assoc
selon la doc : Retourne une ligne de données de l'ensemble de résultats et la renvoie sous forme de tableau associatif.
<br/><br/>
regarder : https://www.php.net/manual/fr/mysqli-result.fetch-assoc.php <br/>
bonne idée ici pour l'affichage : https://tecfa.unige.ch/guides/php/php5_fr/function.mysqli-fetch-assoc.html

</div>
</body>
</html>
