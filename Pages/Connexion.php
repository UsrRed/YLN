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
<?php include('../includes/header.php'); ?>
<div class ="container mt-5">
    
 <h1>Connexion</h1>
    <form action="./traitement/trait_connexion.php" method="post">
        <label for="utilisateur">Nom d'utilisateur :</label>
        <input type="text" id="utilisateur" name="utilisateur" required><br><br>

        <label for="motdepasse">Mot de passe :</label>
        <input type="password" id="motdepasse" name="motdepasse" required><br><br>

        <input type="submit" value="Se connecter">
    </form>

 <h1>Inscription </h1>
    <form action="./traitement/trait_inscription.php" method="post">
        <label for="nouv_utilisateur">Nouveau nom d'utilisateur :</label>
        <input type ="text" id="nouv_utilisateur" name="nouv_utilisateur" required><br><br>

        <label for="nouv_motdepasse">Nouveau mot de passe :</label>
        <input type="password" id="nouv_motdepasse" name="nouv_motdepasse" required><br><br>

        <input type="submit" value="S'inscrire">
    </form>

</div>
</body>
</html>
