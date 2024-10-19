<?php
declare(strict_types=1);

use App\Infrastructure\Slim\Actions\Categories\CreateCategoryAction;
use App\Infrastructure\Slim\Actions\Categories\DeleteCategoryAction;
use App\Infrastructure\Slim\Actions\Categories\GetCategoriesAction;
use App\Infrastructure\Slim\Actions\Categories\ShowCategoryAction;
use App\Infrastructure\Slim\Actions\Categories\UpdateCategorieAction;
use App\Infrastructure\Slim\Actions\Personas\CreatePersonAction;
use App\Infrastructure\Slim\Actions\Personas\DeletePersonAction;
use App\Infrastructure\Slim\Actions\Personas\GetPersonsAction;
use App\Infrastructure\Slim\Actions\Personas\ShowPersonAction;
use App\Infrastructure\Slim\Actions\Personas\UpdatePersonAction;
use App\Infrastructure\Slim\Middleware\AddJsonResponseHeader;
use App\Infrastructure\Slim\Middleware\EnableCorsSupport;
use Slim\Factory\AppFactory;
use DI\ContainerBuilder;
use Slim\Routing\RouteCollectorProxy;

define ('APP_ROOT', dirname(__DIR__));

require APP_ROOT . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(APP_ROOT);
$dotenv->safeLoad();

$builder = new ContainerBuilder();

$container = $builder
            ->addDefinitions(APP_ROOT . '/config/database.php')
            ->addDefinitions(APP_ROOT . '/config/definitions.php')
            ->addDefinitions(APP_ROOT . '/config/persons_definitions.php')
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

$app->options('/{routes:.+}', function ($request, $response) {
    return $response;
});
//Agrego soporte para cors
$app->add(new EnableCorsSupport());

$app->add(new AddJsonResponseHeader());

$app->setBasePath($_ENV['APP_PATH']);

$app->group('/v1', function (RouteCollectorProxy $group) {
    $group->get('/categorias/{id:[0-9]+}', ShowCategoryAction::class);
    $group->patch('/categorias/{id:[0-9]+}', UpdateCategorieAction::class);
    $group->delete('/categorias/{id:[0-9]+}', DeleteCategoryAction::class);
    $group->post('/categorias', CreateCategoryAction::class);
    $group->get('/categorias', GetCategoriesAction::class);
    //ABM Personas
    $group->get('/personas/{id:[0-9]+}', ShowPersonAction::class);
    $group->patch('/personas/{id:[0-9]+}', UpdatePersonAction::class);
    $group->delete('/personas/{id:[0-9]+}', DeletePersonAction::class);
    $group->post('/personas', CreatePersonAction::class);
    $group->get('/personas', GetPersonsAction::class);
    //AMB Gastos
    $group->get('/gastos/{id:[0-9]+}', ShowPersonAction::class);
    $group->patch('/gastos/{id:[0-9]+}', UpdatePersonAction::class);
    $group->delete('/gastos/{id:[0-9]+}', DeletePersonAction::class);
    $group->post('/gastos', \App\Infrastructure\Slim\Actions\Gastos\CreateBillAction::class);
    $group->get('/gastos', \App\Infrastructure\Slim\Actions\Gastos\GetBillsAction::class);
});

$app->run();