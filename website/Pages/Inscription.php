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
        <h1>Inscription</h1>
        <form action="/trait_inscription" method="post">
            <div class="form-group">
                <label for="nouv_utilisateur">Nouveau nom d'utilisateur :</label>
                <input type="text" class="form-control" id="nouv_utilisateur" name="nouv_utilisateur" placeholder="Entrez votre nouveau nom d'utilisateur" required>
            </div>

            <div class="form-group">
                <label for="nouv_motdepasse">Nouveau mot de passe :</label>
                <input type="password" class="form-control" id="nouv_motdepasse" name="nouv_motdepasse" placeholder="Entrez votre nouveau mot de passe" required>
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-danger">S'inscrire</button>

            </div>
        </form>
    </div>
</body>

</html>

