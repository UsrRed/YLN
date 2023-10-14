<?php
session_start(); // Démarrer la session

if (!isset($_SESSION['utilisateur'])) {
    // Si l'utilisateur n'est pas connecté, redirigez-le vers la page de connexion
    header("Location: /Connexion");
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Comparaison</title>
</head>
<body>

<?php


$nom_utilisateur = $_SESSION['utilisateur']; #Pour récupérer le nom d'utilisateur depuis la session

# On fait la connexion à la base de données
include('/home/Pages/configBDD/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $comparaison1 = $_POST["comparaison1"];
    $comparaison2 = $_POST["comparaison2"];

    if (!empty($comparaison1) && !empty($comparaison2)) {
        #On récupère l'ID de l'utilisateur avec une Requête sql
        $query_utilisateur = "SELECT id FROM Utilisateur WHERE nom_utilisateur = ?";
        $stmt = $connexion->prepare($query_utilisateur);
        $stmt->bind_param("s", $nom_utilisateur);
        $stmt->execute();
        $result_utilisateur = $stmt->get_result();
	# On remarque si l'uilisateur est trouvé comme ça on aura son ID avec la colonne dans la table utilisateurs
        if ($result_utilisateur && $result_utilisateur->num_rows == 1) {
            $row_utilisateur = $result_utilisateur->fetch_assoc();
            $utilisateur_id = $row_utilisateur['id']; #La on a l'ID de l'utilisateur
        } else {
            #Cas où l'utilisateur n'est pas trouvé, go redirigier vers la page de connexion ???????????????????
        }
	$stmt->close(); 

        #Requête SQL pour inséré les données de la comparaison dans la table Historique
        $sql = "INSERT INTO Historique (utilisateur_id, comparaison1, comparaison2, date) VALUES (?, ?, ?, NOW())";

        $stmt = $connexion->prepare($sql);
        $stmt->bind_param("iss", $utilisateur_id, $comparaison1, $comparaison2);

        if ($stmt->execute()) {
            echo "Vous avez comparé $comparaison1 avec $comparaison2";
        } else {
            echo "Une erreur est survenue lors de l'ajout de la comparaison : " . $stmt->error;
        }
        $stmt->close(); 
    } else {
        echo "Veuillez entrer des valeurs pour les comparaisons.";
    }
}

$connexion->close();
?>

</body>
</html>
