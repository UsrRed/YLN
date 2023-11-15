<?php


if (isset($_GET["entity1"]) && isset($_GET["entity2"])) {
    $comparaison1 = $_GET["entity1"];
    $comparaison1 = filter_var($comparaison1, FILTER_UNSAFE_RAW);
    $comparaison2 = $_GET["entity2"];
    $comparaison2 = filter_var($comparaison2, FILTER_UNSAFE_RAW);

    #Requête API MediaWiki pour les deux entités. On récupère les données dans les variables au format json (elle utilise json_decode)
    $data1 = recupWikiData($comparaison1);
    $data2 = recupWikiData($comparaison2);

    $datainfo1 = recupWikiDataInfo($comparaison1);
    $datainfo2 = recupWikiDataInfo($comparaison2);

    $infobox1 = "";
    $infobox2 = "";

    $info1 = "";
    $info2 = "";

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
    $attributs_fusionnes = array();

    if (isset($datainfo1['query']['pages'])) {
            $page_info1 = reset($datainfo1['query']['pages']); #Comme d'hab, on prend la première page (il n'y en a qu'une normalement)

            if (isset($page_info1['length'])) $page_long1 = $page_info1['length'];
            else $page_long1 = "";
            if (isset($page_info1['protection'])) $page_protection1 = $page_info1['protection'];
            else $page_protection1 = "";
            if (isset($page_info1['touched'])) $page_modif1 = $page_info1['touched'];
            else $page_modif1 = "";
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
            if (isset($page_info2['watchers'])) $page_watchers2 = $page_info2['watchers'];
            else $page_watchers2 = "";
            if (isset($page_info2['fullurl'])) $page_url2 = $page_info2['fullurl'];
            else $page_url2 = "";
    }
    $attributs_fusionnes['longueur'] = array($page_long1, $page_long2);
    $attributs_fusionnes['modification'] = array($page_modif1, $page_modif2);
    $attributs_fusionnes['vues'] = array($page_watchers1, $page_watchers2);
    $attributs_fusionnes['url'] = array($page_url1, $page_url2);


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
    $final_table = array();

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
                    } else {
                            $final_table[$attribut]=array($val1, $val2);
                    }
            } else {
                    # erreur
            }
    }
    $json_request = json_encode($final_table);
    echo $json_request;
}

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

function traitement($attribut, $valeur)
{
        # fonction pour filtrer l'affichage et le rendre plus propre
        $banned_attributs = array(
                'couleurboîte', 'titreblanc', 'logo', 'taillelogo', 'nometlogo', 'nomidentifiant', 'taille', 'espace', 'tailledrapeau');
        $banned_caracters = array('Langue|en|texte=', '[', ']', '{', '}', '|');

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

function recupWikiDataInfo($Titre)
{
        $URL1 = "https://fr.wikipedia.org/w/api.php?action=query&format=json&titles=" . urlencode($Titre) . "&prop=info&inprop=protection|talkid|watched|watchers|visitingwatchers|notificationtimestamp|subjectid|url|readable|preload|displaytitle|normalizedtitle|prefixedtitle|delegated&origin=*";
        $response = file_get_contents($URL1);
        return json_decode($response, true);

}

#On sauvegarde des variables dans la session de l'utilisateur pour faire le téléchargement

?>