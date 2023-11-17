<?php
// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() == PHP_SESSION_NONE) session_start();

// Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
if (!isset($_SESSION['utilisateur_id'])) {
        // Définir un message et une couleur de statut pour la redirection
        $_SESSION['status'] = "primary";
        $_SESSION['message'] = "Vous devez être connecté, redirection sur la page de connexion...";

        // Rediriger vers la page de connexion
        header("Location: /Connexion");
        exit();
}

function replaceElementsInText($text)
{
        // pris sur internet
        // Utiliser une expression régulière pour détecter les éléments dans le format ::element1||element2::
        $pattern = '/::(.*?)\|\|(.*?)::/';
        $replacedText = preg_replace_callback($pattern, function ($matches) {
                // Créer un bouton cliquable avec l'élément correspondant
                return '<form action="/trait_comparaison" method="post" class="d-inline">
                    <input type="hidden" name="comparaison1" value="' . $matches[1] . '">
                    <input type="hidden" name="comparaison2" value="' . $matches[2] . '">
                    <button type="submit" class="btn btn-info btn-sm">' . $matches[1] . '|' . $matches[2] . '</button>
                </form>';
        }, $text);

        return $replacedText;
}


// Récupérer les informations de l'utilisateur connecté
$nom_utilisateur = $_SESSION['utilisateur'];
$id_utilisateur = $_SESSION['utilisateur_id'];
?>

<?php include('/home/includes/header.php'); ?>

<body class="bg-light">
<?php
// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['utilisateur_id'])) {
        // Inclure le fichier de configuration de la base de données
        include('/home/Pages/configBDD/config.php');
        ?>
    <!-- Rajoute un css personnalisé -->
    <style><?php include("/home/includes/CSS/chat.css") ?></style>

        <?php afficher_etat(); ?>
    <!-- Conteneur du chat -->
    <div class="container mt-5">
        <!-- Affichage des messages du chat -->
        <div class="card" style="height: 300px; overflow-y: auto;">
            <div class="card-header">
                Derniers messages
            </div>
            <div class="card-body">
                    <?php
                    // Récupérer les derniers messages de la table des messages (remplacer avec les vrais noms de colonnes)
                    $sql = "SELECT Messages.*, Utilisateur.nom_utilisateur as nom_envoyeur FROM Messages, Utilisateur WHERE Messages.utilisateur_id = Utilisateur.id ORDER BY Messages.date DESC LIMIT 10";

                    // Exécuter la requête SQL
                    $resultat = $connexion->query($sql);

                    // Afficher les messages s'il y en a
                    if ($resultat->num_rows > 0) {
                    // Afficher les données de chaque ligne
                    while ($ligne = $resultat->fetch_assoc()) {
                    ?>
                <div class="media mb-3">
                        <?php
                        if ($nom_utilisateur == $ligne['nom_envoyeur']) {
                                echo "<div class=\"media-body text-right\">";
                        } elseif ($ligne['nom_envoyeur'] == 'admin') {
                                echo "<div class=\"media-body text-left\">";
                        } else {
                                echo "<div class=\"media-body text-left\">";
                        }
                        ?>
                    <h5 class="mt-0 font-weight-bold <?php
                    if ($nom_utilisateur == $ligne['nom_envoyeur']) {
                            echo "text-success";
                    } elseif ($ligne['nom_envoyeur'] == 'admin') {
                            echo "text-danger";
                    }
                    ?>"><?php echo filter_var($ligne['nom_envoyeur'], FILTER_UNSAFE_RAW); ?></h5>
                    <div class="p-2 rounded <?php
                    if ($nom_utilisateur == $ligne['nom_envoyeur']) {
                            echo "chat-green chat-right";
                    } elseif ($ligne['nom_envoyeur'] == 'admin') {
                            echo "chat-red chat-left";
                    } else {
                            echo "chat-gray chat-left";
                    }
                    ?>">
                            <?php
                            // Appelle la fonction pour remplacer les éléments dans le texte
                            $replacedText = replaceElementsInText($ligne['texte']);
                            echo filter_var($replacedText, FILTER_UNSAFE_RAW);
                            ?>
                        <div class="text-muted">
                                <?php echo filter_var($ligne['date'], FILTER_UNSAFE_RAW); ?>
                                <?php if ($nom_utilisateur != $ligne['nom_envoyeur']) { ?>
                                    <form action="/trait_chat_likes" method="post" class="d-inline">
                                        <input type="hidden" name="action" value="like">
                                        <input type="hidden" name="message_id"
                                               value="<?php echo filter_var($ligne['message_id'], FILTER_UNSAFE_RAW); ?>">
                                        <button type="submit" class="btn btn-success btn-sm">
                                            &#8679; <?php echo filter_var($ligne['like_count'], FILTER_UNSAFE_RAW); ?></button>
                                    </form>
                                    <form action="/trait_chat_likes" method="post" class="d-inline">
                                        <input type="hidden" name="action" value="dislike">
                                        <input type="hidden" name="message_id"
                                               value="<?php echo filter_var($ligne['message_id'], FILTER_UNSAFE_RAW); ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            &#8681; <?php echo filter_var($ligne['dislike_count'], FILTER_UNSAFE_RAW); ?></button>
                                    </form>
                                <?php } else { ?>
                                    <span class="font-weight-bold text-success">&#8679; <?php echo filter_var($ligne['like_count'], FILTER_UNSAFE_RAW); ?></span>
                                    <span class="font-weight-bold text-danger">&#8681; <?php echo filter_var($ligne['dislike_count'], FILTER_UNSAFE_RAW); ?></span>
                                <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
                <?php
                }
                } else {
                        echo "Aucun message pour le moment.";
                }
                ?>
        </div>
    </div>
    <!-- Formulaire pour envoyer un message -->
        <?php
        // Vérifier si le paramètre GET 'partage' est défini
        if (isset($_GET['partage'])) {
                // Récupérer la valeur de 'partage'
                $partageValue = filter_var($_GET['partage'], FILTER_UNSAFE_RAW);
        } else {
                // Si 'partage' n'est pas défini, utiliser une valeur par défaut (par exemple, chaîne vide)
                $partageValue = '';
        }
        ?>
    <form action="/trait_chat" method="post" class="mt-3">
        <div class="form-group">
            <label for="message-input">Tapez votre message...</label>
            <input type="text" name="message" id="message-input" class="form-control"
                   value="<?php echo $partageValue; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Envoyer</button>
    </form>
    </div>
        <?php
} ?>
</body>
</html>
