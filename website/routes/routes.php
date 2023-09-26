<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Slim\Views\PhpRenderer;
use App\Application\Settings\SettingsInterface;

return function (App $app) {
  // prepare the setting for all the route with 'use($settings)'
  $container = $app->getContainer();
  $settings = $container->get(SettingsInterface::class);
  
    //Retourne si la page de connexion existe
    $app->get('/Pages/connexion', function (Request $request, Response $response) {
      $response->getBody()->write('Connexion');
      return $response;
    });

    //retourne si le lien entre la BD et le serveur web est actif
    $app->get('/Pages/Connexion', function (Request $request, Response $response) {

        // Récupération des données du formulaire
        $user = $_POST['utilisateur'];
        $pass = $_POST['motdepasse'];

        $dbHost = 'mysql';
        $port = '3306';
        $dbName = 'nathiotime';
        $dbUser = 'root';
        $dbPass = 'root';

        // Connexion à la base de données + verrification de la connexion
        $connexion = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName, $port);
        if ($connexion->connexion_error) {
            die("Connection failed: " . $connexion->connexion_error);
            return $response = 1;
        }else{
            return $response = 0;
        }

/*
        // Requête SQL pour vérifier si l'utilisateur existe
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE nom_utilisateur = :user AND mot_de_passe = :pass");
        $stmt->bindParam(':user', $user);
        $stmt->bindParam(':pass', $pass);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $verif = json_encode($rows);

        $body=$response->getBody();
        $body->write($verif);
        return $response->withHeader('Content-type', 'application/json');

        // Fermeture de la connexion à la base de données
        $connexion->close();

        // Vérifie le nombre de lignes retournées par la requête
        $rowCount = $stmt->rowCount();

        if ($rowCount > 0) {
            echo "L'utilisateur existe dans la base de données.";
        } else {
            echo "L'utilisateur n'existe pas dans la base de données ou les informations sont incorrectes.";
        }

*/

        $app->get('/phpinfo', function (Request $request, Response $response) {
            phpinfo();
            return $response;
        });

    });

};

