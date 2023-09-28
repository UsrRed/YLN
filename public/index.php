<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../composer/vendor/autoload.php';

/**
 * Instantiate App
 *
 * In order for the factory to work you need to ensure you have installed
 * a supported PSR-7 implementation of your choice e.g.: Slim PSR-7 and a supported
 * ServerRequest creator (included with Slim PSR-7)
 */
$app = AppFactory::create();

/**
  * The routing middleware should be added earlier than the ErrorMiddleware
  * Otherwise exceptions thrown from it will not be handled by the middleware
  */
$app->addRoutingMiddleware();

/**
 * Add Error Middleware
 *
 * @param bool                  $displayErrorDetails -> Should be set to false in production
 * @param bool                  $logErrors -> Parameter is passed to the default ErrorHandler
 * @param bool                  $logErrorDetails -> Display error details in error log
 * @param LoggerInterface|null  $logger -> Optional PSR-3 Logger  
 *
 * Note: This middleware should be added last. It will not handle any exceptions/errors
 * for middleware added after it.
 */
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// Define app routes
$app->get('/hello/{name}', function (Request $request, Response $response, $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");
    return $response;
});

#Aide pour les routes : https://openclassrooms.com/forum/sujet/a-quoi-sert-obstart-27525

$app->get('/Inscription', function (Request $request, Response $response, $args) {

    $file = '/home/Pages/Inscription.php';
    ob_start(); #Mise en mémoire "tampon" pour stocker temporairement le fichier
    include($file); #Inclu le fichier dans la mise en mémoire "tampon"
    $output = ob_get_clean(); #Récupère le contenu du tampon de sortie

    #Ecrit le contenu du fichier dans la variable réponse
    $response->getBody()->write($output);

    return $response;
});   

$app->get('/', function (Request $request, Response $response, $args) {

    $file = '/home/index.php';

    ob_start();
    include($file);
    $output = ob_get_clean(); 
    $response->getBody()->write($output);
    return $response;

});

$app->get('/Historique', function (Request $request, Response $response, $args) {

    $file = '/home/Pages/Historique.php';

    ob_start();
    include($file);
    $output = ob_get_clean(); 
    $response->getBody()->write($output);
    return $response;

}); 

$app->get('/Connexion', function (Request $request, Response $response, $args) {

    $file = '/home/Pages/Connexion.php';

    ob_start(); 
    include($file); 
    $output = ob_get_clean(); 
    $response->getBody()->write($output);

    return $response;

}); 

$app->get('/Favoris', function (Request $request, Response $response, $args) {

    $file = '/home/Pages/Favoris.php';

    ob_start(); 
    include($file); 
    $output = ob_get_clean(); 
    $response->getBody()->write($output);

    return $response;

}); 


$app->get('/trait_deconnexion', function (Request $request, Response $response, $args) {

    $file = '/home/Pages/traitement/trait_deconnexion.php';

    ob_start(); 
    include($file); 
    $output = ob_get_clean(); 
    $response->getBody()->write($output);
 
    return $response;

});


$app->post('/trait_inscription', function (Request $request, Response $response, $args) {

    $file = '/home/Pages/traitement/trait_inscription.php';

    ob_start(); 
    include($file); 
    $output = ob_get_clean(); 
    $response->getBody()->write($output);
 
    return $response;

});

$app->post('/trait_comparaison', function (Request $request, Response $response, $args) {

    $file = '/home/Pages/traitement/trait_comparaison.php';

    ob_start(); 
    include($file); 
    $output = ob_get_clean(); 
    $response->getBody()->write($output);
 
    return $response;

});


$app->post('/trait_connexion', function (Request $request, Response $response, $args) {

    $file = '/home/Pages/traitement/trait_connexion.php';

    ob_start(); 
    include($file); 
    $output = ob_get_clean(); 
    $response->getBody()->write($output);
 
    return $response;

});

// Run app
$app->run();


