<?php include('/home/includes/header.php'); 

if (!isset($_SESSION['nb_inscri'])) {
    $_SESSION['nb_inscri'] = 1;
}
$_SESSION['nb_inscri']++;

?>

<body class="bg-light">
<div class="container mt-5">
    <h1>Inscription</h1>
        <?php afficher_etat(); ?>
    <form action="/trait_inscription" method="post">
        <div class="form-group">
            <label for="nouv_utilisateur">Nouveau nom d'utilisateur :</label>
            <input type="text" class="form-control" id="nouv_utilisateur" name="nouv_utilisateur"
                   placeholder="Entrez votre nouveau nom d'utilisateur" required>
            <small class="text-muted">Votre nom d'utilisateur doit être entre 3 et 25 caractères</small>
        </div>

        <div class="form-group">
            <label for="nouv_motdepasse">Nouveau mot de passe :</label>
            <input type="password" class="form-control" id="nouv_motdepasse" name="nouv_motdepasse"
                   placeholder="Entrez votre nouveau mot de passe" required>
            <small class="text-muted">Votre mot de passe doit faire 8 caractères et avoir 1 chiffre, 1 majuscule, 1 minuscule, 1 caractère spécial au minimum</small>
        </div>
        
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-success">S'inscrire</button>
        </div>
    </form>
</div>
</body>
