<?php
if (session_status() == PHP_SESSION_NONE) session_start(); # Pour démarrer la session
if (!isset($_SESSION['utilisateur'])) {
        $_SESSION['status'] = "primary";
        $_SESSION['message'] = "Vous devez être connecté, redirection sur la page de connexion...";
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
afficher_etat();

$nom_utilisateur = $_SESSION['utilisateur']; #Pour récupérer le nom d'utilisateur depuis la session
#echo "nom_utilisateur";
#echo "nom_utilisateur";
#echo "test";
# On fait la connexion à la base de données
include('/home/Pages/configBDD/config.php');
#echo "test";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
$comparaison1 = $_POST["comparaison1"];
$comparaison1 = filter_var($comparaison1, FILTER_UNSAFE_RAW);
$comparaison1 = htmlspecialchars($comparaison1);
$comparaison2 = $_POST["comparaison2"];
$comparaison2 = filter_var($comparaison2, FILTER_UNSAFE_RAW);
$comparaison2 = htmlspecialchars($comparaison2);
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
$data1 = recupWikiData($comparaison1);
$data2 = recupWikiData($comparaison2);

$datainfo1 = recupWikiDataInfo($comparaison1);
$datainfo2 = recupWikiDataInfo($comparaison2);

#echo $data1;
#echo $data2;
#Infobox entière, manque plus qu'à les parser pour avoir les données qu'on souhaite

$infobox1 = "";
$infobox2 = "";

$info1 = "";
$info2 = "";

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
        #Contient donc les informations entières de l'infobox pour la première comparaison
        if (isset($temp['revisions'][0]['*'])) $infobox1 = $temp['revisions'][0]['*'];
        else $infobox1 = "";
        #echo "$infobox1";
} else {
        echo "Aucune infobox trouvée pour la page $comparaison1";
}
# On fait pareil pour la deuxième comparaison/entité
if (isset($data2['query']['pages'])) {
        $temp = reset($data2['query']['pages']);
        if (isset($temp['revisions'][0]['*'])) $infobox2 = $temp['revisions'][0]['*'];
        else $infobox2 = "";
        #echo "testtt";
} else {

        echo "Aucune infobox trouvée pour la page $comparaison2";
}

#Pour les infos de la page :


if (isset($datainfo1['query']['pages'])) {
        $page_info1 = reset($datainfo1['query']['pages']); #Comme d'hab, on prend la première page (il n'y en a qu'une normalement)

        if (isset($page_info1['length'])) $page_long1 = $page_info1['length'];
        else $page_long1 = "";
        if (isset($page_info1['protection'])) $page_protection1 = $page_info1['protection'];
        else $page_protection1 = "";
        if (isset($page_info1['touched'])) $page_modif1 = $page_info1['touched'];
        else $page_modif1 = "";
        $page_modif1 = preg_replace('/T([0-9]{2}:[0-9]{2}:[0-9]{2})Z/', ' à ${1}', $page_modif1);
        if (isset($page_info1['watchers'])) $page_watchers1 = $page_info1['watchers'];
        else $page_watchers1 = "";
        if (isset($page_info1['fullurl'])) $page_url1 = $page_info1['fullurl'];
        else $page_url1 = "";
}


if (isset($datainfo2['query']['pages'])) { #Pareil
        $page_info2 = reset($datainfo2['query']['pages']);

        if (isset($page_info2['length'])) $page_long2 = $page_info2['length'];
        else $page_long2 = "";
        if (isset($page_info2['protection'])) $page_protection2 = $page_info2['protection'];
        else $page_protection2 = "";
        if (isset($page_info2['touched'])) $page_modif2 = $page_info2['touched'];
        else $page_modif2 = "";
        $page_modif2 = preg_replace('/T([0-9]{2}:[0-9]{2}:[0-9]{2})Z/', ' à ${1}', $page_modif2);
        if (isset($page_info2['watchers'])) $page_watchers2 = $page_info2['watchers'];
        else $page_watchers2 = "";
        if (isset($page_info2['fullurl'])) $page_url2 = $page_info2['fullurl'];
        else $page_url2 = "";
}

#echo "Watchers : $page_watchers1";

?>

<div class="container mt-5">

    <h2><u><?php echo $comparaison1; ?></u> VS <u><?php echo $comparaison2; ?></u> :</h2>
    <!--A changer, trouver un moyen, horrible -->

        <?php

        #------------------------------------PARSEMENT DES INFOBOX------------------------------------#

        #A cet instant, toutes les données des infoboxes sont stockés dans la variable infobox1 et infobox2, on utilise maintenant des REGEX pour les parser et ainsi avoir les attributs qu'on veut
        #--> Regex faites dans un tableau ci-dessous

        #------------------------------------AFFICHAGE TABLEAU ATTRIBUTS-------------------------------#

        #Affiche l'attributs des deux entités s'ils ont cette information, sinon ça ne l'affiche pas. Affichement sous forme de tableau

        ?>
    <br/><br/>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Attributs</th>
            <th><?php echo $comparaison1;
            if ($infobox1=="") echo " (page vide)"; ?>
            </th>
            <th><?php echo $comparaison2;
            if ($infobox2=="") echo " (page vide)"; ?>
            </th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>URL</td>
            <td><a href='<?php echo $page_url1; ?>'><?php echo $page_url1; ?> </a></td>
            <td><a href='<?php echo $page_url2; ?>'><?php echo $page_url2; ?></a></td>
        </tr>
        <?php
        if ($page_long1!="" || $page_long2!="") {
        ?>
        <tr>
            <td>Longueur de la page</td>
            <td>
            <?php echo $page_long1;
            if ($page_long1!=""){echo " octets";} ?>
            </td>
            <td>
            <?php echo $page_long2;
            if ($page_long2!=""){echo " octets";} ?>
            </td>
        </tr>
        <?php
        }
        if ($page_modif1!="" || $page_modif2!="") {
        ?>
        <tr>
            <td>Dernière modification</td>
            <td><?php echo $page_modif1; ?></td>
            <td><?php echo $page_modif2; ?> </td>
        </tr>
        <?php
        }
        if ($page_watchers1!="" || $page_watchers2!="") {
        ?>
        <tr>
            <td>Nombre de favoris</td>
            <td><?php echo $page_watchers1; ?></td>
            <td><?php echo $page_watchers2; ?></td>
        </tr>
        <?php
        }

        $filtre_infobox = '/\{\{Infobox ([\s\S]*?)(?:\s\|\s)([\s\S]+?\\n\}\})/m';
        $filtre_attr_value = '/([^=]+) =([\s\S]*?)(?:\s\|\s|\\n\}\})/m';

        preg_match_all($filtre_infobox, $infobox1, $matchinfobox1, PREG_SET_ORDER, 0);
        if (isset($matchinfobox1[0][2])) {
                preg_match_all($filtre_attr_value, $matchinfobox1[0][2], $liste_infos1, PREG_SET_ORDER, 0);
        } else {
                $liste_infos1 = array();
        }
        preg_match_all($filtre_infobox, $infobox2, $matchinfobox2, PREG_SET_ORDER, 0);
        if (isset($matchinfobox2[0][2])) {
        preg_match_all($filtre_attr_value, $matchinfobox2[0][2], $liste_infos2, PREG_SET_ORDER, 0);
        } else {
                $liste_infos2 = array();
        }
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
                if (count($valeurs) == 0) {
                        # Donnée sans valeur
                } elseif (count($valeurs) == 1) {
                        # Uniquement d'un côté
                        /*if (isset($valeurs[0])) {
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
                            }*/

                } elseif (count($valeurs) == 2) {
                        $val1 = traitement(simplify($attribut), $valeurs[0]);
                        $val2 = traitement(simplify($attribut), $valeurs[1]);
                        if ($val1 == '' || $val2 == '') {
                                # données éléminées
                        } elseif ($val1 == $val2) {
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
        #echo "<br/><br/>";
        #On récupère l'ID de la comparaison pour ensuite mettre en place les favoris
        #On ajoute l'id de la comparaison dans la table favoris pour avoir la ligne sur laquelle l'utilisateur a mis le favoris

        $req_id_comparaison = "SELECT id FROM Historique WHERE comparaison1 = '$comparaison1' AND comparaison2 = '$comparaison2'";
        $resultat_id_comparaison = mysqli_query($connexion, $req_id_comparaison);
        $ligne_id_comparaison = mysqli_fetch_assoc($resultat_id_comparaison);
        $id_comparaison = $ligne_id_comparaison['id'];

        #ech
        ?>

        <!--On utilise le même style des boutons que pour la page Connexion -->

        <div class="container mt-3 d-flex justify-content-between">
            <form method="post" action="/trait_telechargement" class="d-flex justify-content-between">
                <button type="submit" class="btn btn-dark" name="telecharger_csv">Télécharger le CSV</button>
            </form>
            <a class="btn btn-dark" href="/accueil">Faire une autre comparaison</a>
            <a class="btn btn-primary" href="/chat?partage=<?php echo "::" . $comparaison1 . "||" . $comparaison2 . "::"; ?>">Partager</a>
            <form method="post" action="/trait_favoris">
                <input type="hidden" name="comparaison_id" value="<?php echo $id_comparaison; ?>">
                <button type="submit" class="btn btn-success" name="ajouter_favoris">Ajouter aux favoris</button>
            </form>
        </div>

        <?php

        }

        $connexion->close();

        function recupWikiData($Titre)
        { #Décupère les données des pages depuis l'API MediaWiki
                $URL = "https://fr.wikipedia.org/w/api.php?action=query&format=json&prop=revisions&titles=" . urlencode($Titre) . "&rvprop=content&origin=*"; #URL pour les données des infobox
                $response = file_get_contents($URL); #Effectue du requête GET à l'URL de l'API
                return json_decode($response, true); #Pour mettre le format json
        }

        function simplify($attribut)
        {
                $attribut = str_replace(' ', '', $attribut);
                return $attribut;
        }

        function replaceUrlsWithSpans($text) {
            // Expression régulière pour extraire les URLs dans le texte
            $pattern = '/\b(?:https?:\/\/\S+)\b/';

            // Fonction de traitement des correspondances
            $processMatch = function($match) {
                $url = $match[0];

                // Utilise parse_url pour extraire le domaine de l'URL
                $parsedUrl = parse_url($url);
                $domain = isset($parsedUrl['host']) ? $parsedUrl['host'] : '';

                // Construit le lien <span> avec le texte du lien comme domaine
                $linkText = $domain;
                $span = "<a href=\"https://$linkText\">$linkText</a>";

                return $span;
            };

            // Applique la fonction de traitement des correspondances dans le texte
            $result = preg_replace_callback($pattern, $processMatch, $text);

            return $result;
        }

        function traitement($attribut, $valeur)
        {
                # fonction pour filtrer l'affichage et le rendre plus propre
                $banned_attributs = array(
                        'couleurboîte', 'couleurécriture' , 'couleurcadre', 'titreblanc', 'logo', 'taillelogo', 'nometlogo', 'nomidentifiant', 'taille', 'espace', 'tailledrapeau');
                $banned_attributs_regex = array(
                        '/(pattern_...)/', '/(pattern_..)/', '/(socks.)/'
                );
                $banned_caracters = array('Langue|en|texte=', '[', ']', '{', '}', '|', 'url=', 'URL');
                $banned_caracters_regex = array(
                        '/(...-d)/', '/(\(.+\))/'
                );

                foreach ($banned_attributs as $test) {
                        if ($test == $attribut) {
                                return "";
                        }
                }
                $valeur = replaceUrlsWithSpans($valeur);

                foreach ($banned_attributs_regex as $regex){
                    if (preg_replace($regex, '', $attribut) == "") {
                            return "";
                    }
                }


                if (preg_replace('/\s+/', '', $valeur) == "") {
                        $valeur = '';
                }

                foreach ($banned_caracters as $modification) {
                        $valeur = str_replace($modification, '', $valeur);
                }

                foreach ($banned_caracters_regex as $regex){
                        $valeur = preg_replace($regex, '', $valeur);
                }
                $valeur = preg_replace('/T([0-9]{2}:[0-9]{2}:[0-9]{2})Z/', ' à ${1}', $valeur);

                return $valeur;
        }

        function recupWikiDataInfo($Titre)
        {
                $URL1 = "https://fr.wikipedia.org/w/api.php?action=query&format=json&titles=" . urlencode($Titre) . "&prop=info&inprop=protection|talkid|watched|watchers|visitingwatchers|notificationtimestamp|subjectid|url|readable|preload|displaytitle|normalizedtitle|prefixedtitle|delegated&origin=*";
                $response = file_get_contents($URL1);
                return json_decode($response, true);
    
        }

        #On sauvegarde des variables dans la session de l'utilisateur pour faire le téléchargement

        $_SESSION['attributs_fusionnes'] = $attributs_fusionnes;
        $_SESSION['comparaison1'] = $comparaison1;
        $_SESSION['comparaison2'] = $comparaison2;

        ?>

</div>
<br/>
<!--<p>https://fr.wikipedia.org/w/api.php?action=query&format=json&prop=revisions&titles=Porsche&rvprop=content&origin=*</p>-->
</body>
</html>
