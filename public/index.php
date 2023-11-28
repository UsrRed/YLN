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
 * @param bool $displayErrorDetails -> Should be set to false in production
 * @param bool $logErrors -> Parameter is passed to the default ErrorHandler
 * @param bool $logErrorDetails -> Display error details in error log
 * @param LoggerInterface|null $logger -> Optional PSR-3 Logger
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

        $fich = '/home/Pages/Inscription.php';
        ob_start(); #Mise en mémoire "tampon" pour stocker temporairement le fichier
        include($fich); #Inclu le fichier dans la mise en mémoire "tampon"
        $output = ob_get_clean(); #Récupère le contenu du tampon de sortie

        #Ecrit le contenu du fichier dans la variable réponse
        $response->getBody()->write($output);

        return $response;
});

$app->get('/', function (Request $request, Response $response, $args) {

        $fich = '/home/Pages/PopUp.php';

        ob_start();
        include($fich);
        $output = ob_get_clean();
        $response->getBody()->write($output);
        return $response;

});

$app->get('/accueil', function (Request $request, Response $response, $args) {

        $fich = '/home/index.php';

        ob_start();
        include($fich);
        $output = ob_get_clean();
        $response->getBody()->write($output);
        return $response;

});

$app->get('/Historique', function (Request $request, Response $response, $args) {

        $fich = '/home/Pages/Historique.php';

        ob_start();
        include($fich);
        $output = ob_get_clean();
        $response->getBody()->write($output);
        return $response;

});

$app->get('/Connexion', function (Request $request, Response $response, $args) {

        $fich = '/home/Pages/Connexion.php';

        ob_start();
        include($fich);
        $output = ob_get_clean();
        $response->getBody()->write($output);

        return $response;

});

$app->get('/Favoris', function (Request $request, Response $response, $args) {

        $fich = '/home/Pages/Favoris.php';

        ob_start();
        include($fich);
        $output = ob_get_clean();
        $response->getBody()->write($output);

        return $response;

});


$app->get('/trait_deconnexion', function (Request $request, Response $response, $args) {

        $fich = '/home/Pages/traitement/trait_deconnexion.php';

        ob_start();
        include($fich);
        $output = ob_get_clean();
        $response->getBody()->write($output);

        return $response;

});


$app->post('/trait_inscription', function (Request $request, Response $response, $args) {

        $fich = '/home/Pages/traitement/trait_inscription.php';

        ob_start();
        include($fich);
        $output = ob_get_clean();
        $response->getBody()->write($output);

        return $response;

});

$app->post('/trait_comparaison', function (Request $request, Response $response, $args) {

        $fich = '/home/Pages/traitement/trait_comparaison.php';

        ob_start();
        include($fich);
        $output = ob_get_clean();
        $response->getBody()->write($output);

        return $response;

});

$app->get('/comparaisons', function (Request $request, Response $response, $args) {

        $fich = '/home/Pages/traitement/API_REST.php';

        ob_start();
        include($fich);
        $output = ob_get_clean();
        $response->getBody()->write($output);

        return $response
            ->withHeader('Content-Type', 'application/json');

});

$app->post('/trait_connexion', function (Request $request, Response $response, $args) {

        $fich = '/home/Pages/traitement/trait_connexion.php';

        ob_start();
        include($fich);
        $output = ob_get_clean();
        $response->getBody()->write($output);

        return $response;

});

$app->post('/trait_favoris', function (Request $request, Response $response, $args) {

        $fich = '/home/Pages/traitement/trait_favoris.php';

        ob_start();
        include($fich);
        $output = ob_get_clean();
        $response->getBody()->write($output);

        return $response;

});

$app->get('/Paramètres', function (Request $request, Response $response, $args) {
        $fich = '/home/Pages/Paramètres.php';

        ob_start();
        include($fich);
        $output = ob_get_clean();
        $response->getBody()->write($output);

        return $response;
});

$app->post('/trait_changement_mdp', function (Request $request, Response $response, $args) {

        $fich = '/home/Pages/traitement/trait_mdp_changement.php';

        ob_start();
        include($fich);
        $output = ob_get_clean();
        $response->getBody()->write($output);

        return $response;

});

$app->get('/trait_profil', function (Request $request, Response $response, $args) {

        $fich = '/home/Pages/traitement/trait_profil.php';

        ob_start();
        include($fich);
        $output = ob_get_clean();
        $response->getBody()->write($output);

        return $response;

});

$app->get('/trait_mdp_appli', function (Request $request, Response $response, $args) {

        $fich = '/home/Pages/traitement/trait_mdp_appli.php';

        ob_start();
        include($fich);
        $output = ob_get_clean();
        $response->getBody()->write($output);

        return $response;

});

$app->post('/trait_info_profil', function (Request $request, Response $response, $args) {

        $fich = '/home/Pages/traitement/trait_profil_info.php';

        ob_start();
        include($fich);
        $output = ob_get_clean();
        $response->getBody()->write($output);

        return $response;

});

$app->get('/trait_support', function (Request $request, Response $response, $args) {

        $fich = '/home/Pages/traitement/trait_support.php';

        ob_start();
        include($fich);
        $output = ob_get_clean();
        $response->getBody()->write($output);

        return $response;

});

$app->post('/trait_envoi_mail', function (Request $request, Response $response, $args) {

        $fich = '/home/Pages/traitement/trait_envoi_mail.php';

        ob_start();
        include($fich);
        $output = ob_get_clean();
        $response->getBody()->write($output);

        return $response;

});

$app->get('/trait_faq', function (Request $request, Response $response, $args) {

        $fich = '/home/Pages/traitement/trait_faq.php';
        ob_start();
        include($fich);
        $output = ob_get_clean();
        $response->getBody()->write($output);

});

$app->get('/Vue_globale', function (Request $request, Response $response, $args) {

        $fich = '/home/Pages/Vue_globale.php';

        ob_start();
        include($fich);
        $output = ob_get_clean();
        $response->getBody()->write($output);

        return $response;

});

$app->get('/admin', function (Request $request, Response $response, $args) {

        $fich = '/home/Pages/FAQ_admin.php';

        ob_start();
        include($fich);
        $output = ob_get_clean();
        $response->getBody()->write($output);

        return $response;

});

$app->get('/FAQ', function (Request $request, Response $response, $args) {

        $fich = '/home/Pages/FAQ_utilisateur.php';

        ob_start();
        include($fich);
        $output = ob_get_clean();
        $response->getBody()->write($output);

        return $response;

});

$app->post('/trait_suppression', function (Request $request, Response $response, $args) {

        $fich = '/home/Pages/traitement/trait_suppression.php';

        ob_start();
        include($fich);
        $output = ob_get_clean();
        $response->getBody()->write($output);

        return $response;

});

$app->get('/trait_changement_mdp_formulaire', function (Request $request, Response $response, $args) {

        $fich = '/home/Pages/traitement/trait_mdp_changement_formulaire.php';

        ob_start();
        include($fich);
        $output = ob_get_clean();
        $response->getBody()->write($output);

        return $response;

});


$app->get('/trait_suppression_formulaire', function (Request $request, Response $response, $args) {

        $fich = '/home/Pages/traitement/trait_suppression_formulaire.php';

        ob_start();
        include($fich);
        $output = ob_get_clean();
        $response->getBody()->write($output);

        return $response;

});

$app->get('/trait_mdp_oublie_formulaire', function (Request $request, Response $response, $args) {

        $fich = '/home/Pages/traitement/trait_mdp_oublie_formulaire.php';

        ob_start();
        include($fich);
        $output = ob_get_clean();
        $response->getBody()->write($output);

        return $response;

});

$app->post('/trait_mdp_oublie', function (Request $request, Response $response, $args) {

        $fich = '/home/Pages/traitement/trait_mdp_oublie.php';

        ob_start();
        include($fich);
        $output = ob_get_clean();
        $response->getBody()->write($output);

        return $response;

});

$app->post('/trait_telechargement', function (Request $request, Response $response, $args) {

        $fich = '/home/Pages/traitement/trait_telechargement.php';

        ob_start();
        include($fich);
        $output = ob_get_clean();
        $response->getBody()->write($output);

        return $response;

});

$app->get('/trait_blocage', function (Request $request, Response $response, $args) {

        $fich = '/home/Pages/traitement/trait_blocage.php';

        ob_start();
        include($fich);
        $output = ob_get_clean();
        $response->getBody()->write($output);

        return $response;

});


$app->get('/trait_reinitialisation_mdp_formulaire', function (Request $request, Response $response, $args) {

        $fich = '/home/Pages/traitement/trait_reinitialisation_mdp_formulaire.php';

        ob_start();
        include($fich);
        $output = ob_get_clean();
        $response->getBody()->write($output);

        return $response;

});

$app->post('/trait_reinitialisation_mdp', function (Request $request, Response $response, $args) {

        $fich = '/home/Pages/traitement/trait_reinitialisation_mdp.php';

        ob_start();
        include($fich);
        $output = ob_get_clean();
        $response->getBody()->write($output);

        return $response;

});

$app->get('/chat', function (Request $request, Response $response, $args) {

        $fich = '/home/Pages/Chat.php';

        ob_start();
        include($fich);
        $output = ob_get_clean();
        $response->getBody()->write($output);

        return $response;
});

$app->post('/trait_chat', function (Request $request, Response $response, $args) {

        $fich = '/home/Pages/traitement/trait_chat.php';

        ob_start();
        include($fich);
        $output = ob_get_clean();
        $response->getBody()->write($output);

        return $response;

});

$app->post('/trait_chat_likes', function (Request $request, Response $response, $args) {

        $fich = '/home/Pages/traitement/trait_chat_likes.php';
        ob_start();
        include($fich);
        $output = ob_get_clean();
        $response->getBody()->write($output);

        return $response;
});

$app->post('/trait_suppression_historique', function (Request $request, Response $response, $args) {

        $fich = '/home/Pages/traitement/trait_suppression_historique.php';
        ob_start();
        include($fich);
        $output = ob_get_clean();
        $response->getBody()->write($output);

        return $response;
});

$app->post('/trait_suppression_favoris', function (Request $request, Response $response, $args) {

        $fich = '/home/Pages/traitement/trait_suppression_favoris.php';
        ob_start();
        include($fich);
        $output = ob_get_clean();
        $response->getBody()->write($output);

        return $response;
});

$app->get('/trait_info_application', function (Request $request, Response $response, $args) {

        $fich = '/home/Pages/traitement/trait_info_application.php';
        ob_start();
        include($fich);
        $output = ob_get_clean();
        $response->getBody()->write($output);

        return $response;
});

$app->post('/trait_stat', function (Request $request, Response $response, $args) {

        $fich = '/home/Pages/traitement/trait_stat.php';
        ob_start();
        include($fich);
        $output = ob_get_clean();
        $response->getBody()->write($output);

        return $response;
});

$app->get('/phpinfo', function (Request $request, Response $response) {
        phpinfo();
        return $response;
});

#Run app
$app->run();

