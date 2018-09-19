<?php
/**
 * ARQUIVO DE CONFIGURAÇÃO DE ROTAS
 **/

require_once '../BackEnd/vendor/autoload.php';

use \Slim\Http\Request;
use \Slim\Http\Response;

//instancia o slim
//$app = new \Slim\App;
$app = new \Slim\App([
    'settings' => [
        // Only set this if you need access to route within middleware
        'determineRouteBeforeAppMiddleware' => true
    ]
]);

// obtem um container
$container = $app->getContainer();

// Registra componente no container para abilitar o html render
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig('/var/www/html/portalPmro/FrontEnd/View', [
        'cache' => false
    ]);
    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

    return $view;
};

//abilita Cros Domain
$app->options('/{routes:.+}', function (Request $request,  Response $response, $args) {
    return $response->withStatus(200);
});

$app->add(function ($req, $res, $next) {

    $response = $next($req, $res);
    //$origin = $req->getHeader('Host') ? $req->getHeader('Host') : 'http://192.168.133.12';
    $origin = $req->getHeader('Origin') ? $req->getHeader('Origin') : 'http://192.168.133.12';

    return $response
        ->withHeader('Access-Control-Allow-Credentials', 'true')
        ->withHeader('Access-Control-Allow-Origin', $origin)
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});


//REGISTRA O MIDDLEWARE IP_ADDRES
$checkProxyHeaders = false; // Note: Never trust the IP address for security processes!
$trustedProxies = ['192.168.66.111']; // Note: Never trust the IP address for security processes!
$app->add(new Portal\Middleware\IpAddressMiddleware($checkProxyHeaders, $trustedProxies));

// Render html em rota
// ROTAS DE WEBPAGES
require_once '../BackEnd/Routes/web.php';

// ROTAS DE WEBSERVICE REST
require_once '../BackEnd/Routes/webservice.php';

$app->run();