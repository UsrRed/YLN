<?php include('/home/includes/header.php'); ?>

<body class="bg-light">
<div class="container mt-5">
    <h1>Inscription</h1>
        <?php afficher_etat(); ?>
    <form action="/trait_inscription" method="post">
        <div class="form-group">
            <label for="nouv_utilisateur">Nouveau nom d'utilisateur :</label>
            <input type="text" class="form-control" id="nouv_utilisateur" name="nouv_utilisateur"
                   placeholder="Entrez votre nouveau nom d'utilisateur" required>
        </div>

        <div class="form-group">
            <label for="nouv_motdepasse">Nouveau mot de passe :</label>
            <input type="password" class="form-control" id="nouv_motdepasse" name="nouv_motdepasse"
                   placeholder="Entrez votre nouveau mot de passe" required>
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-success">S'inscrire</button>
        </div>
    </form>
</div>
</body>
