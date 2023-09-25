<?php
#Connexion à la base de données :

include('./../configBDD/config.php');

#Récupérer les données du formulaire

$utilisateur = $_POST['utilisateur'];
$motdepasse = $_POST['motdepasse'];

$message = "";

#Vérification si l'utilisateur existe bien

$query = "SELECT * FROM Utilisateur WHERE nom_utilisateur='$utilisateur' AND mot_de_passe='$motdepasse'";
$result = mysqli_query($connexion, $query);

if ($result) {
    #Vérification si une ligne dans la BDD a été trouvée
    if (mysqli_num_rows($result) ==1) {
        #Si 1, l'utilisateur est connecté, c'est ok    
    #session_start();
    
#REGARDER COMMENT REDIRIGER VERS L'ACCUEIL, LOCATION JE CROISS 
	    
	    $message = "Vous êtes connecté";
    } else {
        #Si la variable result n'est pas de 1, l'utilisateur n'existe pas
        $message = "Vous n'êtes pas connecté, les informations de connexion sont incrorectes";
    }
} else {
    #Erreur de requête
    $message = "Erreur de requête : " . mysqli_error($connexion);
}

echo $message;
?>
