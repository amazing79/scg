<?php

use App\Application\Personas\CreatePersonaCommandHandler;
use App\Application\Personas\DeletePersonaCommandHandler;
use App\Application\Personas\GetPersonaByIdQueryHandler;
use App\Application\Personas\GetPersonasQueryHandler;
use App\Application\Personas\UpdatePersonasCommandHandler;
use App\Infrastructure\Common\Database;
use App\Infrastructure\Personas\PdoPersonasRepository;
use DI\Container;

return [
    PdoPersonasRepository::class => function (Container $container) {
        return new PdoPersonasRepository($container->get(Database::class));
    },
    GetPersonasQueryHandler::class =>  function (Container $container) {
        return new GetPersonasQueryHandler(
            $container->get(PdoPersonasRepository::class),
            null
        );
    },
    GetPersonaByIdQueryHandler::class => function (Container $container) {
        return new GetPersonaByIdQueryHandler($container->get(PdoPersonasRepository::class));
    },
    CreatePersonaCommandHandler::class => function (Container $container) {
        return new CreatePersonaCommandHandler($container->get(PdoPersonasRepository::class));
    },
    UpdatePersonasCommandHandler::class => function (Container $container) {
        return new UpdatePersonasCommandHandler($container->get(PdoPersonasRepository::class));
    },
    DeletePersonaCommandHandler::class => function (Container $container) {
        return new DeletePersonaCommandHandler($container->get(PdoPersonasRepository::class));
    }
];
