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

// Parse json, form data and xml
$app->addBodyParsingMiddleware();

$app->addRoutingMiddleware();

/**
 * Add Error Middleware
 * Note: This middleware should be added last. It will not handle any exceptions/errors
 * for middleware added after it.
 */
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorHandler = $errorMiddleware->getDefaultErrorHandler();
$errorHandler->forceContentType('application/json');

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
    return $response->withHeader('Content-Type', 'application/json')->withStatus($result['code']);
});

$app->patch('/categorias/{id:[0-9]+}', function (Request $request, Response $response, $args) {
    $db = new Database(
        $_ENV['DB_HOST'],
        $_ENV['DB_NAME'],
        $_ENV['DB_USER'],
        $_ENV['DB_PASS']
    );
    $repository = new \App\Infrastructure\Categorias\PdoCategoriasRepository($db);
    $command = new \App\Application\Categorias\UpdateCategoriaCommandHandler($repository);
    $values = $request->getParsedBody();
    $values['id'] = $args['id'];
    $result = $command->handle($values);
    $dataAsJson = json_encode($result, JSON_PRETTY_PRINT);

    $response->getBody()->write($dataAsJson);
    return $response->withHeader('Content-Type', 'application/json')->withStatus($result['code']);;
});

$app->delete('/categorias/{id:[0-9]+}', function (Request $request, Response $response, $args) {
    $db = new Database(
        $_ENV['DB_HOST'],
        $_ENV['DB_NAME'],
        $_ENV['DB_USER'],
        $_ENV['DB_PASS']
    );
    $repository = new \App\Infrastructure\Categorias\PdoCategoriasRepository($db);
    $command = new \App\Application\Categorias\DeleteCategoriaByIdCommandHandler($repository);
    $id = $args['id'];
    $result = $command->handle((int) $id);
    $dataAsJson = json_encode($result, JSON_PRETTY_PRINT);

    $response->getBody()->write($dataAsJson);
    return $response->withHeader('Content-Type', 'application/json')->withStatus($result['code']);;
});

$app->post('/categorias', function (Request $request, Response $response) {
    $db = new Database(
        $_ENV['DB_HOST'],
        $_ENV['DB_NAME'],
        $_ENV['DB_USER'],
        $_ENV['DB_PASS']
    );
    $repository = new \App\Infrastructure\Categorias\PdoCategoriasRepository($db);
    $command = new \App\Application\Categorias\CreateCategoriaCommandHandler($repository);
    $values = $request->getParsedBody();
    $result = $command->handle($values);
    $dataAsJson = json_encode($result, JSON_PRETTY_PRINT);

    $response->getBody()->write($dataAsJson);
    return $response->withHeader('Content-Type', 'application/json')->withStatus($result['code']);
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