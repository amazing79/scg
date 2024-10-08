<?php
declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use DI\ContainerBuilder;

define ('APP_ROOT', dirname(__DIR__));

require APP_ROOT . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(APP_ROOT);
$dotenv->safeLoad();

$builder = new ContainerBuilder();

$container = $builder
            ->addDefinitions(APP_ROOT . '/config/database.php')
            ->addDefinitions(APP_ROOT . '/config/definitions.php')
            ->build();

AppFactory::setContainer($container);

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

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', $_ENV['ORIGIN'] ?? '*')
        ->withHeader('Vary', 'Origin')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

$app->setBasePath($_ENV['APP_PATH']);

$app->get('/categorias/{id:[0-9]+}', function (Request $request, Response $response, $args) {
    $query = $this->get(\App\Application\Categorias\GetCategoriaById::class);
    $id = $args['id'];
    $result = $query->handle((int) $id);
    $dataAsJson = json_encode($result, JSON_PRETTY_PRINT);

    $response->getBody()->write($dataAsJson);
    return $response->withHeader('Content-Type', 'application/json')->withStatus($result['code']);
});

$app->patch('/categorias/{id:[0-9]+}', function (Request $request, Response $response, $args) {
    $repository = $this->get(\App\Infrastructure\Categorias\PdoCategoriasRepository::class);
    $command = new \App\Application\Categorias\UpdateCategoriaCommandHandler($repository);
    $values = $request->getParsedBody();
    $values['id'] = $args['id'];
    $result = $command->handle($values);
    $dataAsJson = json_encode($result, JSON_PRETTY_PRINT);

    $response->getBody()->write($dataAsJson);
    return $response->withHeader('Content-Type', 'application/json')->withStatus($result['code']);
});

$app->delete('/categorias/{id:[0-9]+}', function (Request $request, Response $response, $args) {
    $repository = $this->get(\App\Infrastructure\Categorias\PdoCategoriasRepository::class);
    $command = new \App\Application\Categorias\DeleteCategoriaByIdCommandHandler($repository);
    $id = $args['id'];
    $result = $command->handle((int) $id);
    $dataAsJson = json_encode($result, JSON_PRETTY_PRINT);

    $response->getBody()->write($dataAsJson);
    return $response->withHeader('Content-Type', 'application/json')
                    ->withStatus($result['code']);
});

$app->post('/categorias', function (Request $request, Response $response) {
    $repository = $this->get(\App\Infrastructure\Categorias\PdoCategoriasRepository::class);
    $command = new \App\Application\Categorias\CreateCategoriaCommandHandler($repository);
    $values = $request->getParsedBody();
    $result = $command->handle($values);
    $dataAsJson = json_encode($result, JSON_PRETTY_PRINT);

    $response->getBody()->write($dataAsJson);
    return $response->withHeader('Content-Type', 'application/json')->withStatus($result['code']);
});

$app->get('/categorias', function (Request $request, Response $response) {
    $query = $this->get(\App\Application\Categorias\GetCategoriasQueryHandler::class);
    $result = $query->handle();
    $dataAsJson = json_encode($result, JSON_PRETTY_PRINT);

    $response->getBody()->write($dataAsJson);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();