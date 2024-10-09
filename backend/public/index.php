<?php
declare(strict_types=1);

use App\Infrastructure\Slim\Actions\Categories\CreateCategoryAction;
use App\Infrastructure\Slim\Actions\Categories\DeleteCategoryAction;
use App\Infrastructure\Slim\Actions\Categories\GetCategoriesAction;
use App\Infrastructure\Slim\Actions\Categories\ShowCategoryAction;
use App\Infrastructure\Slim\Actions\Categories\UpdateCategorieAction;
use App\Infrastructure\Slim\Middleware\AddJsonResponseHeader;
use App\Infrastructure\Slim\Middleware\EnableCorsSupport;
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
//Agrego soporte para cors
$app->add(new EnableCorsSupport());

$app->add(new AddJsonResponseHeader());

$app->setBasePath($_ENV['APP_PATH']);

$app->get('/categorias/{id:[0-9]+}', ShowCategoryAction::class);

$app->patch('/categorias/{id:[0-9]+}', UpdateCategorieAction::class);

$app->delete('/categorias/{id:[0-9]+}', DeleteCategoryAction::class);

$app->post('/categorias', CreateCategoryAction::class);

$app->get('/categorias', GetCategoriesAction::class);

$app->run();