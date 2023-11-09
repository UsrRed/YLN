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
#echo "nom_utilisateur";
#echo "nom_utilisateur";
#echo "test";
# On fait la connexion à la base de données
include('/home/Pages/configBDD/config.php');
#echo "test";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$comparaison1 = $_POST["comparaison1"];
	$comparaison2 = $_POST["comparaison2"];
	#echo "$comparaison1 $comparaison2";
	#echo "test";

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
	#echo "$comparaison1";
	#echo "$comparaison2";
	#$connexion->close();

	#Requête API MediaWiki pour les deux entités. On récupère les données dans les variables au format json (elle utilise json_decode)
	$data1 = fetchWikiData($comparaison1);
	$data2 = fetchWikiData($comparaison2);

	#echo $data1;
	#echo $data2;
	#Infobox entière, manque plus qu'à les parser pour avoir les données qu'on souhaite

	$infobox1 = "";
	$infobox2 = "";

	#--------------------------ATTRIBUTS A CRÉER-----------------------------#

	#Données qu'on récupère (variables des infoboxes) : 
	#$nomComplet1 = "";
	#$nomComplet2 = "";
	#$nom1 = "";
	#$nom2 = "";

	#......

	#------------------------------------------------------------------------#

	#Ensuite on extrait les données des infoboxes

	if (isset($data1['query']['pages'])) { #On vérifie d'abord que la requête à abouti et que la réponse contient le mot clef pages dans le tableau query pour la prmeière entité qu'on veut comparer.
		#En gros, MediaWiki répond avec un tableau et query contient les résultats de la requête vers l'API et dans ces résultats il y a "pages". Et si la page n'existe pas (celle de Wikipédia), les variables 
		#sont toujours là mais elles sont juste vides. On a vu ça avec : https://www.mediawiki.org/wiki/API:Main_page/fr et surtout : https://www.mediawiki.org/wiki/API:Query#Response, voir Example 1 : Specifying pages dans Response.
		$temp = reset($data1['query']['pages']); #On met la variable "temp" en index 1 (premier élément) du tableau, une seule page Wikipédia en gros, simplicité
		#echo "$temp";
            	$infobox1 = $temp['revisions'][0]['*']; #Contient donc les informations entières de l'infobox pour la première comparaison
		#echo "$infobox1";

	}
	# On fait pareil pour la deuxième comparaison/entité
	if (isset($data2['query']['pages'])) {
		$temp = reset($data2['query']['pages']);
		$infobox2 = $temp['revisions'][0]['*'];
		#echo "testtt";
	}

	?>

	<div class="container mt-5">

	<h2><u><?php echo $comparaison1; ?></u> VS  <u><?php echo $comparaison2; ?></u> :</h2> <!--A changer, trouver un moyen, horrible -->

	<?php

	#------------------------------------PARSEMENT DES INFOBOX------------------------------------#

	#A cet instant, toutes les données des infoboxes sont stockés dans la variable infobox1 et infobox2, on utilise maintenant des REGEX pour les parser et ainsi avoir les attributs qu'on veut
	#--> Regex faites dans un tableau ci-dessous	

	#------------------------------------AFFICHAGE TABLEAU ATTRIBUTS-------------------------------#

	#Affiche l'attributs des deux entités s'ils ont cette information, sinon ça ne l'affiche pas. Affichement sous forme de tableau

	?>
		<br/><br/>
		<table class="table table-bordered"><thead><tr><th>Attributs</th><th><?php echo $comparaison1; ?></th><th><?php echo $comparaison2;?></th></tr></thead><tbody>
	<?php

	$filtre_infobox = '/\{\{Infobox ([\s\S]*?)(?:\s\|\s)([\s\S]+?\\n\}\})/m';
	$filtre_attr_value = '/([^=]+) =([\s\S]*?)(?:\s\|\s|\\n\}\})/m';
	preg_match_all($filtre_infobox, $infobox1, $matchinfobox1, PREG_SET_ORDER, 0);
	preg_match_all($filtre_attr_value, $matchinfobox1[0][2], $liste_infos1, PREG_SET_ORDER, 0);
	preg_match_all($filtre_infobox, $infobox2, $matchinfobox2, PREG_SET_ORDER, 0);
	preg_match_all($filtre_attr_value, $matchinfobox2[0][2], $liste_infos2, PREG_SET_ORDER, 0);

	# Le groupe 0 est le texte avant filtrage
    # Le groupe 1 constitue les attributs, le groupe 2 les valeurs
    /*
	if (count($liste_infos1[count($liste_infos1)-1])>2){
        $temp = $liste_infos1[count($liste_infos1)-1];
        $liste_infos1[count($liste_infos1)-1] = [$temp[0], $temp[3], $temp[4], '', ''];
	}
	if (count($liste_infos2[count($liste_infos2)-1])>2){
        $temp = $liste_infos2[count($liste_infos2)-1];
        $liste_infos2[count($liste_infos2)-1] = [$temp[0], $temp[3], $temp[4], '', ''];
    }
    */

	$attributs_fusionnes = array();

	foreach ($liste_infos1 as $element) {
        $attribut = $element[1];
        $valeur = $element[2];
        if (!isset($attributs_fusionnes[$attribut])) {
                $attributs_fusionnes[$attribut] = array();
        }
        $attributs_fusionnes[$attribut][0] = $valeur;
	}
	foreach ($liste_infos2 as $element) {
        $attribut = $element[1];
        $valeur = $element[2];
        if (!isset($attributs_fusionnes[$attribut])) {
                $attributs_fusionnes[$attribut] = array();
        }
        $attributs_fusionnes[$attribut][1] = $valeur;
    }

    # var_dump($attributs_fusionnes);

	foreach ($attributs_fusionnes as $attribut => $valeurs) {
	    if (count($valeurs)==0) {
	        # Donnée sans valeur
	    } elseif (count($valeurs)==1) {
	        # Uniquement d'un côté
	        if (isset($valeurs[0])) {
	            $val = traitement(simplify($attribut), $valeurs[0]);
	            if ($val == '') {
                    # données éléminées
                } else {
                    echo "<tr><td>$attribut</td><td>$val</td><td></td></tr>";
                }
	        }
	        if (isset($valeurs[1])) {
                $val = traitement(simplify($attribut), $valeurs[1]);
                if ($val == '') {
                    # données éléminées
                } else {
                    echo "<tr><td>$attribut</td><td></td><td>$val</td></tr>";
                }
            }
	    } elseif (count($valeurs)==2) {
	        $val1 = traitement(simplify($attribut), $valeurs[0]);
            $val2 = traitement(simplify($attribut), $valeurs[1]);
            if ($val1 == '' || $val2 == '') {
                # données éléminées
            }elseif ($val1 == $val2) {
                # Même valeur : fusion du tableau
                echo "<tr><td>$attribut</td><td colspan='2'><center>$val1</td><tr>";
            } else {
                echo "<tr><td>$attribut</td><td>$val1</td><td>$val2</td></tr>";
            }
	    } else {
	        # erreur
	    }
	}

	echo "</tbody></table>";
	echo "<br/><br/>";
	#On récupère l'ID de la comparaison pour ensuite mettre en place les favoris
	#On ajoute l'id de la comparaison dans la table favoris pour avoir la ligne sur laquelle l'utilisateur a mis le favoris

	$req_id_comparaison = "SELECT id FROM Historique WHERE comparaison1 = '$comparaison1' AND comparaison2 = '$comparaison2'";
	$resultat_id_comparaison = mysqli_query($connexion, $req_id_comparaison);
	$ligne_id_comparaison = mysqli_fetch_assoc($resultat_id_comparaison);
	$id_comparaison = $ligne_id_comparaison['id'];

	#ech
	echo '<div class="text-center mt-3">';
	echo '<form method="post" action="/trait_favoris">';
    	echo '<input type="hidden" name="comparaison_id" value="' . $id_comparaison . '">';
    	echo '<button type="submit" class="btn btn-danger" name="ajouter_favoris">Ajouter aux favoris</button>';
	echo '</form>';
	echo '</div>';
    } else {
        echo "Veuillez entrer des valeurs pour les comparaisons.";
}

$connexion->close();
#Fonction dont nous ne sommes pas l'auteur. @Author : Owen, https://www.developpez.com 

function fetchWikiData($Titre) { #Décupère les données des pages depuis l'API MediaWiki
	$URL = "https://fr.wikipedia.org/w/api.php?action=query&format=json&prop=revisions&titles=" . urlencode($Titre) . "&rvprop=content&origin=*"; #URL
	$response = file_get_contents($URL); #Effectue du requête GET à l'URL de l'API
	return json_decode($response, true); #Pour mettre le format json
}

function simplify($attribut) {
    $attribut = str_replace(' ', '', $attribut);
    return $attribut;
}

function traitement($attribut, $valeur) {
    # fonction pour filtrer l'affichage et le rendre plus propre
    $banned_attributs = array(
    'couleurboîte', 'titreblanc', 'logo', 'taillelogo', 'nometlogo', 'nomidentifiant', 'taille',
    'espace', 'tailledrapeau'
    );
    $banned_caracters = array(
    'Langue|en|texte=', '[', ']', '{', '}', '|'
    );
    foreach ($banned_attributs as $test) {
        if ($test == $attribut) {
            return "";
        }
    }
    if (preg_replace('/\s+/', '', $valeur) == "") {
        $valeur = '';
    }
    foreach ($banned_caracters as $modification) {
        $valeur = str_replace($modification, '', $valeur);
    }
    return $valeur;
}
?>
</div>
<br/><br/>
<p>https://fr.wikipedia.org/w/api.php?action=query&format=json&prop=revisions&titles=Porsche&rvprop=content&origin=*</p>
</body>
</html>
