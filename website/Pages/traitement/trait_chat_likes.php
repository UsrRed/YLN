<?php
if (session_status() == PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['utilisateur_id'])) {
        $_SESSION['status'] = "primary";
        $_SESSION['message'] = "Vous devez être connecté, redirection sur la page de connexion...";
        header("Location: /Connexion");
        exit();
}

include('/home/Pages/configBDD/config.php');

if (isset($_POST['action']) && isset($_POST['message_id'])) {
        # Sanitiser les données
        $action = filter_var($_POST['action'], FILTER_UNSAFE_RAW);
        $message_id = filter_var($_POST['message_id'], FILTER_SANITIZE_NUMBER_INT);

        # Vérifier si l'action est valide (like ou dislike)
        if ($action == 'like' || $action == 'dislike') {
                # Vérifier si l'utilisateur a déjà liké
                $sqlChecklike = "SELECT * FROM LikesDislikes WHERE id_utilisateur = " . $_SESSION['utilisateur_id'] . " AND id_message = $message_id AND like_bool=1";
                $resultChecklike = $connexion->query($sqlChecklike);
                if ($resultChecklike->num_rows > 0) {
                        $liked = True;
                } else {
                        $liked = False;
                }
                $sqlCheckdislike = "SELECT * FROM LikesDislikes WHERE id_utilisateur = " . $_SESSION['utilisateur_id'] . " AND id_message = $message_id AND dislike_bool=1";
                $resultCheckdislike = $connexion->query($sqlCheckdislike);
                if ($resultCheckdislike->num_rows > 0) {
                        $disliked = True;
                } else {
                        $disliked = False;
                }

                $sqlRemove = "DELETE FROM LikesDislikes WHERE id_utilisateur = " . $_SESSION['utilisateur_id'] . " AND id_message = $message_id";
                $connexion->query($sqlRemove);
                if ($action == 'like') {
                        # veux liker/enlever le like
                        if ($liked) {
                                # si il a déjà un like
                                $sqlUpdate = "UPDATE Messages SET like_count = like_count - 1 WHERE message_id = $message_id";
                                $sqlInsert = "INSERT INTO LikesDislikes (id_utilisateur, id_message, like_bool, dislike_bool) VALUES ({$_SESSION['utilisateur_id']}, $message_id, 0, 0 );";
                        } else {
                                # si il n'a pas encore de like
                                $sqlUpdate = "UPDATE Messages SET like_count = like_count + 1 WHERE message_id = $message_id";
                                $sqlInsert = "INSERT INTO LikesDislikes (id_utilisateur, id_message, like_bool, dislike_bool) VALUES ({$_SESSION['utilisateur_id']}, $message_id, 1, 0 );";
                                if ($disliked) {
                                        # si il a un dislike
                                        $sqlUpdate2 = "UPDATE Messages SET dislike_count = dislike_count - 1 WHERE message_id = $message_id";
                                        $connexion->query($sqlUpdate2);
                                }
                        }
                } else {
                        # veux disliker/enlever le dislike
                        if ($disliked) {
                                # Si il a déjà dislike
                                $sqlUpdate = "UPDATE Messages SET dislike_count = dislike_count - 1 WHERE message_id = $message_id";
                                $sqlInsert = "INSERT INTO LikesDislikes (id_utilisateur, id_message, like_bool, dislike_bool) VALUES ({$_SESSION['utilisateur_id']}, $message_id, 0, 0 );";
                        } else {
                                # si il n'a pas encore dislike
                                $sqlUpdate = "UPDATE Messages SET dislike_count = dislike_count + 1 WHERE message_id = $message_id";
                                $sqlInsert = "INSERT INTO LikesDislikes (id_utilisateur, id_message, like_bool, dislike_bool) VALUES ({$_SESSION['utilisateur_id']}, $message_id, 0, 1 );";
                                if ($liked) {
                                        # si il a un like
                                        $sqlUpdate2 = "UPDATE Messages SET like_count = like_count - 1 WHERE message_id = $message_id";
                                        $connexion->query($sqlUpdate2);
                                }
                        }
                }
                $connexion->query($sqlUpdate);
                $connexion->query($sqlInsert);
        }
}

# Fermer la connexion à la base de données
$connexion->close();
header("Location: /chat");
?>
