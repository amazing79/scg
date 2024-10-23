<?php

use App\Application\Gastos\CreateGastoCommandHandler;
use App\Application\Gastos\DeleteGastoCommandHandler;
use App\Application\Gastos\GetGastoByIdQueryHandler;
use App\Application\Gastos\GetGastosQueryHandler;
use App\Application\Gastos\UpdateGastoCommandHandler;
use App\Domain\Gastos\GastosPresenter;
use App\Infrastructure\Common\Database;
use App\Infrastructure\Gastos\PdoGastosRepository;
use DI\Container;

return [
    PdoGastosRepository::class => function (Container $container) {
        return new PdoGastosRepository($container->get(Database::class));
    },
    GetGastosQueryHandler::class =>  function (Container $container) {
        return new GetGastosQueryHandler(
            $container->get(PdoGastosRepository::class),
            new GastosPresenter()
        );
    },
    GetGastoByIdQueryHandler::class => function (Container $container) {
        return new GetGastoByIdQueryHandler($container->get(PdoGastosRepository::class));
    },
    CreateGastoCommandHandler::class => function (Container $container) {
        return new CreateGastoCommandHandler($container->get(PdoGastosRepository::class));
    },
    UpdateGastoCommandHandler::class => function (Container $container) {
        return new UpdateGastoCommandHandler($container->get(PdoGastosRepository::class));
    },
    DeleteGastoCommandHandler::class => function (Container $container) {
        return new DeleteGastoCommandHandler($container->get(PdoGastosRepository::class));
    }
];