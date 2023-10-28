<?php
session_start(); # Pour démarrer la session

if (!isset($_SESSION['utilisateur'])) {
    #Si l'utilisateur n'est pas connecté, on vers la page de connexion
    header("Location: /Connexion");
    exit();
}
?>

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
<?php
$nom_utilisateur = $_SESSION['utilisateur']; #Pour récupérer le nom d'utilisateur depuis la session

# On fait la connexion à la base de données
include('/home/Pages/configBDD/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $comparaison1 = $_POST["comparaison1"];
    $comparaison2 = $_POST["comparaison2"];

    if (!empty($comparaison1) && !empty($comparaison2)) {
        # On récupère l'ID de l'utilisateur avec une Requête SQL
        $query_utilisateur = "SELECT id FROM Utilisateur WHERE nom_utilisateur = ?";
        $stmt = $connexion->prepare($query_utilisateur);
        $stmt->bind_param("s", $nom_utilisateur);
        $stmt->execute();
        $result_utilisateur = $stmt->get_result();

        $ligne_utilisateur_table = $result_utilisateur->fetch_assoc();
        $utilisateur_id = $ligne_utilisateur_table['id']; # Là on a l'ID de l'utilisateur
        } else {
            echo "L'utilisateur n'a pas été trouvé.";
        }
	$stmt->close();

	$sql = "INSERT INTO Historique (utilisateur_id, comparaison1, comparaison2, date) VALUES (?, ?, ?, NOW())";
	$stmt = $connexion->prepare($sql);
	$stmt->bind_param("iss", $utilisateur_id, $comparaison1, $comparaison2);
	$stmt->execute();
	$stmt->close();
	$connexion->close();

	#Requête API MediaWiki pour les deux entités. On récupère les données dans les variables au format json (elle utilise json_decode)

        $data1 = fetchWikiData($comparaison1);
        $data2 = fetchWikiData($comparaison2);

	#Infobox entière, manque plus qu'à les parser pour avoir les données qu'on souhaite

        $infobox1 = "";
	$infobox2 = "";

	#--------------------------ATTRIBUTS A CRÉER-----------------------------#

	#Données qu'on récupère (variables des infoboxes) : 

	$nomComplet1 = "";
	$nomComplet2 = "";

	$nom1 = "";
	$nom2 = "";


	#.......

	#------------------------------------------------------------------------#

	#Ensuite on extrait les données des infoboxes

	if (isset($data1['query']['pages'])) { #On vérifie d'abord que la requête à abouti et que la réponse contient le mot clef pages dans le tableau query pour la prmeière entité qu'on veut comparer.
		#En gros, MediaWiki répond avec un tableau et query contient les résultats de la requête vers l'API et dans ces résultats il y a "pages". Et si la page n'existe pas (celle de Wikipédia), les variables 
		#sont toujours là mais elles sont juste vides. On a vu ça avec : https://www.mediawiki.org/wiki/API:Main_page/fr et surtout : https://www.mediawiki.org/wiki/API:Query#Response, voir Example 1 : Specifying pages dans Response.
            $temp = reset($data1['query']['pages']); #Si c'est vrai, on met la variable "temp" en index 1 (premier élément) du tableau, une seule page Wikipédia en gros
            $infobox1 = $temp['revisions'][0]['*']; #Contient donc les informations entières de l'infobox pour la première comparaison
        }
	# On fait pareil pour la deuxième comparaison/entité
        if (isset($data2['query']['pages'])) {
            $temp = reset($data2['query']['pages']);
            $infobox2 = $temp['revisions'][0]['*'];
        }

	?>

	<div class="container mt-5">
	
	<h1>Comparaison entre <u><?php echo $comparaison1; ?></u> et <u><?php echo $comparaison2; ?></u> :</h1> <!--A changer, trouver un moyen, horrible -->

	<?php

	#------------------------------------PARSEMENT DES INFOBOX------------------------------------#

	#A cet instant, toutes les données des infoboxes sont stockés dans la variable infobox1 et infobox2, on utilise maintenant des REGEX pour les parser et ainsi avoir les attributs qu'on veut

	if (preg_match('/\| nom complet\s+=\s+(.*?)\n/i', $infobox1, $matches1)) {
    		$nomComplet1 = $matches1[1];
	}	

	# On vérifie aussi pour la comparaison2
	
	if (preg_match('/\| nom complet\s+=\s+(.*)\n/i', $infobox2, $matches2)) {

		#/ et /i pour délimier la REGEX
		#Ensuite on prend exactement le caractère "|" donc on utilise un antislash (car attributs dans les infoboxs sont séparés par des "|"
		#\s+ car j'ai l'impression que les espaces entre l'attribut et le = varie, à revoir quand même
		#le (.*) permet d'avoir la valeur entière de l'attribut (. caractère "n'importe lequel", zéro ou plusieurs (*)
		#\n car à chaque fois ca se termine par un \n dans les infoboxs (pour les sauts de lignes)
		#Aide : php.net/manual/fr/function.preg-match.php (exemple 1, 2 et notes)

    		$nomComplet2 = $matches2[1];
	}
	
	if (preg_match('/\| nom \s+=\s+(.*?)\n/i', $infobox1, $matches3)) {
                $nom1 = $matches3[1];
        }

        # On vérifie aussi pour la comparaison2

        if (preg_match('/\| nom \s+=\s+(.*)\n/i', $infobox2, $matches4)) {

                $nom2 = $matches4[1];
	}

	#if ....

	#------------------------------------AFFICHAGE TABLEAU ATTRIBUTS-------------------------------#

	#Affiche l'attributs des deux entités s'ils ont cette information, sinon ça ne l'affiche pas. Affichement sous forme de tableau

	?>
		<br/><br/>
		<table class="table table-bordered"><thead><tr><th>Attributs</th><th><?php echo $comparaison1; ?></th><th><?php echo $comparaison2;?></th></tr></thead><tbody>
<?php


	if (!empty($nomComplet1) && !empty($nomComplet2) && $nomComplet1 !== $nomComplet2) {
		
		echo"<tr><td>Nom complet</td><td>$nomComplet1</td><td>$nomComplet2</td></tr>";
	}

	elseif (!empty($nomComplet1) && !empty($nomComplet2) && $nomComplet1 === $nomComplet2){
	
		echo"<tr><td>Nom Complet</td><td colspan='2'><center>$nomComplet1</td><tr>";
	
	}

	if (!empty($nom1) && !empty($nom2) && $nom1 !== $nom2) {

                echo"<tr><td>Nom </td><td>$nom1</td><td>$nom2</td></tr>";
        }

        elseif (!empty($nom1) && !empty($nom2) && $nom1 === $nom2){

                echo"<tr><td>Nom </td><td colspan='2'><center>$nom1</td><tr>";

        }

	#... if ....






	echo "</tbody></table>";

    } else {
        echo "Veuillez entrer des valeurs pour les comparaisons.";
    }
}

#$connexion->close();

#Fonction dont nous ne sommes pas l'auteur. @Author : Owen, https://www.developpez.com 

function fetchWikiData($Titre) { #Décupère les données des pages depuis l'API MediaWiki
    $URL = "https://fr.wikipedia.org/w/api.php?action=query&format=json&prop=revisions&titles=" . urlencode($Titre) . "&rvprop=content&origin=*"; #URL
    $response = file_get_contents($URL); #Effectue du requête GET à l'URL de l'API
    return json_decode($response, true); #Pour mettre le format json
}
?>
</div>

<p>https://fr.wikipedia.org/w/api.php?action=query&format=json&prop=revisions&titles=Porsche&rvprop=content&origin=*</p>

</body>
</html>
