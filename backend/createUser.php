<?php
declare(strict_types=1);

use App\Application\Users\CreateUserCommandHandler;
use App\Infrastructure\Users\PdoUsersRepository;
use DI\ContainerBuilder;

const APP_ROOT = __DIR__;

require APP_ROOT . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(APP_ROOT);
$dotenv->safeLoad();

$builder = new ContainerBuilder();

$container = $builder
    ->addDefinitions(APP_ROOT . '/config/database.php')
    ->addDefinitions(APP_ROOT . '/config/users_definitions.php')
    ->build();


$values = [];
$values['userName'] = $argv[1] ?? 'user';
$values['password'] = $argv[2] ?? 'password';
$values['email'] = $argv[3] ?? 'email@example.com';
$values['pepper'] = $_ENV['SECRET'];


//conecto a la base
$repository = $container->get(PdoUsersRepository::class);
// Comando para crear usuario
$command = new CreateUserCommandHandler($repository);
$result = $command->handle($values);
var_dump($result);







