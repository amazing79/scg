<?php
declare(strict_types=1);

use App\Infrastructure\Common\Database;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

define ('APP_ROOT', dirname(__DIR__));

require APP_ROOT . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(APP_ROOT);
$dotenv->safeLoad();

$app = AppFactory::create();

$app->get('/categorias/{id:[0-9]+}', function (Request $request, Response $response, $args) {
    $db = new Database(
        $_ENV['DB_HOST'],
        $_ENV['DB_NAME'],
        $_ENV['DB_USER'],
        $_ENV['DB_PASS']
    );
    $repository = new \App\Infrastructure\Categorias\PdoCategoriasRepository($db);
    $query = new \App\Application\Categorias\GetCategoriaById($repository);
    $id = $args['id'];
    $result = $query->handle((int) $id);
    $dataAsJson = json_encode($result, JSON_PRETTY_PRINT);

    $response->getBody()->write($dataAsJson);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/categorias', function (Request $request, Response $response) {
    $db = new Database(
        $_ENV['DB_HOST'],
        $_ENV['DB_NAME'],
        $_ENV['DB_USER'],
        $_ENV['DB_PASS']
    );
    $repository = new \App\Infrastructure\Categorias\PdoCategoriasRepository($db);
    $query = new \App\Application\Categorias\GetCategoriasQueryHandler($repository);
    $result = $query->handle();
    $dataAsJson = json_encode($result, JSON_PRETTY_PRINT);

    $response->getBody()->write($dataAsJson);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();