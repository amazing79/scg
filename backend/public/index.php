<?php
declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

define ('APP_ROOT', dirname(__DIR__));

require APP_ROOT . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(APP_ROOT);
$dotenv->safeLoad();

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, $args) {
    $output = "Probando si anda bien .dotenv {$_ENV['DB_HOST']} ";
    $response->getBody()->write($output);
    return $response;
});

$app->run();