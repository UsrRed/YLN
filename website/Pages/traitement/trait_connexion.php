<?php
#Connexion à la base de données :

include('/home/Pages/configBDD/config.php');

#Récupérer les données du formulaire

$utilisateur = $_POST['utilisateur'];
$motdepasse = $_POST['motdepasse'];

$message = "";

#Vérification si l'utilisateur existe bien

$req = "SELECT * FROM Utilisateur WHERE nom_utilisateur='$utilisateur' AND mot_de_passe='$motdepasse'";
$resultat = mysqli_query($connexion, $req);

if ($resultat) {
    #Vérification si une ligne dans la BDD a été trouvée
    if (mysqli_num_rows($resultat) ==1) {
        #Si 1, l'utilisateur est connecté, c'est ok    
    	#session_start(); 
    
#REGARDER COMMENT REDIRIGER VERS L'ACCUEIL, LOCATION JE CROISS 
	    
	    $message = "Vous êtes connecté";
    } else {
        #Si la variable resultat n'est pas de 1, l'utilisateur n'existe pas
        $message = "Vous n'êtes pas connecté, les informations de connexion sont incrorectes";
    }
} else {
    #Erreur de requête
    $message = "Erreur de requête.";
}

echo $message;
?>
