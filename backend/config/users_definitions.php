<?php

use App\Application\Users\GetActiveSessionUserQueryHandler;
use App\Application\Users\LoginUserCommandHandler;
use App\Application\Users\logoutUserCommandHandler;
use App\Infrastructure\Common\Database;
use App\Infrastructure\Users\PdoUsersRepository;
use DI\Container;

return [
    PdoUsersRepository::class => function (Container $container) {
        return new PdoUsersRepository($container->get(Database::class));
    },
    LoginUserCommandHandler::class =>  function (Container $container) {
        return new LoginUserCommandHandler(
            $container->get(PdoUsersRepository::class),
            null
        );
    },
    LogoutUserCommandHandler::class =>  function (Container $container) {
        return new LogoutUserCommandHandler($container->get(PdoUsersRepository::class));
    },
    GetActiveSessionUserQueryHandler::class => function (Container $container) {
        return new GetActiveSessionUserQueryHandler($container->get(PdoUsersRepository::class));
    },
];