<!DOCTYPE html>
<html>
<head>
    <title>Comparaison</title>
</head>
<body>

<?php
    
include('./../configBDD/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $comparaison1=$_POST["comparaison1"];
    $comparaison2= $_POST["comparaison2"];
    
    if (!empty($comparaison1) && !empty($comparaison2)) {

        #POUR LE MOMENT JE METS L'ID A 1, ON VERRA APRES
    	$utilisateur_id = 1; 

        #Requête SQL pour insérer les données de la comparaison dans la table "Historique"
        $sql = "INSERT INTO Historique (utilisateur_id, comparaison1, comparaison2, date) VALUES (?, ?, ?, NOW())";

	#Stocke la requête SQL
	#
	$stmt = $connexion->prepare($sql); #POURQUOI LA VARIABLE EST NULLE ?
	#
	
	$stmt->bind_param("iss", $utilisateur_id, $comparaison1, $comparaison2); #Entier (i), et 2x string (ss), pour lier (https://www.php.net/manual/en/mysqli-stmt.bind-param.php)

	#Après on exécute la requête
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

#Enfin on ferme la connexio
$connexion->close();
?>


</body>
</html>



