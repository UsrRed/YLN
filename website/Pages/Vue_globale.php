<?php
if (session_status() == PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['utilisateur_id'])) {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $_SESSION['status'] = "primary";
        $_SESSION['message'] = "Vous devez être connecté, redirection sur la page de connexion...";
        header("Location: /Connexion");
        exit();
}

include('/home/Pages/configBDD/config.php');

$utilisateur_id = $_SESSION['utilisateur_id'];
$nom_utilisateur = $_SESSION['utilisateur'];

if ($nom_utilisateur !== 'admin') {
        header("Location : /accueil");
}

$req_faq = "SELECT FAQ.*, Utilisateur.adresse_email, Utilisateur.nom_utilisateur FROM FAQ, Utilisateur WHERE FAQ.utilisateur_id = Utilisateur.id";
$resultat_faq = $connexion->query($req_faq);
$num_resultat_faq = mysqli_num_rows($resultat_faq);

$req_favoris = "SELECT Favoris.*, Historique.comparaison1, Historique.comparaison2 FROM Favoris, Historique WHERE Favoris.historique_id = Historique.id ORDER BY date_favoris DESC";
$resultat_favoris = mysqli_query($connexion, $req_favoris);
$num_resultat_favoris = mysqli_num_rows($resultat_favoris);

$req_historique = "SELECT * FROM Historique ORDER BY date DESC";
$resultat_historique = mysqli_query($connexion, $req_historique);
$num_resultat_historique = mysqli_num_rows($resultat_historique);

$req_utilisateur = "SELECT * FROM Utilisateur";
$resultat_utilisateur = mysqli_query($connexion, $req_utilisateur);
$num_resultat_utilisateur = mysqli_num_rows($resultat_utilisateur);
?>

<?php include('/home/includes/header.php'); ?>
<body class="bg-light">
<div class="container mt-5">
    <h2>Vue globale</h2><br/>
    <?php afficher_etat(); ?>
    <h2>Chiffres clés</h2>
    <table class="table table-bordered text-center">
        <thead>
        <tr>
            <th>Nombre d'utilisateur</th>
            <th>Nombre de comparaisons</th>
            <th>Nombre de favoris</th>
            <th>Nombre de questions</th>
        </tr>
        </thead>
        <tbody>
            <tr>
            <?php
            echo '<td><b>' . $num_resultat_utilisateur . '</b></td>';
            echo '<td><b>' . $num_resultat_historique . '</b></td>';
            echo '<td><b>' . $num_resultat_favoris . '</b></td>';
            echo '<td><b>' . $num_resultat_faq . '</b></td>';
            ?>
            </tr>
        </tbody>
    </table>
    <?php
    if ($num_resultat_faq>0) {
    ?>
    <h3>Les questions :</h3>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Utilisateur</th>
            <th>Adresse mail</th>
            <th>Objet</th>
            <th>Corps</th>
            <th>Date de Soumission</th>
        </tr>
        </thead>
        <tbody>
        <?php
        while ($ligne = $resultat_faq->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $ligne['nom_utilisateur'] . '</td>';
                echo '<td>' . $ligne['adresse_email'] . '</td>';
                echo '<td>' . $ligne['objet'] . '</td>';
                echo '<td>' . $ligne['corps'] . '</td>';
                echo '<td>' . $ligne['date_submission'] . '</td>';
                echo '</tr>';
        }
        ?>
        </tbody>
    </table>
    <?php
    }
    ?>
</div>
</body>
