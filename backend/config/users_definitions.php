<?php

use App\Application\Users\LoginUserCommandHandler;
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
];