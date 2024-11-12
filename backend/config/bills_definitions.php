<?php

use App\Application\Gastos\CreateGastoCommandHandler;
use App\Application\Gastos\DeleteGastoCommandHandler;
use App\Application\Gastos\GetGastoByIdQueryHandler;
use App\Application\Gastos\GetGastosByCategoriaPersonaPeriodoQueryHandler;
use App\Application\Gastos\GetGastosByPersonaQueryHandler;
use App\Application\Gastos\GetGastosQueryHandler;
use App\Application\Gastos\UpdateGastoCommandHandler;
use App\Domain\Gastos\GastoCategoriaPresenter;
use App\Domain\Gastos\GastosPresenter;
use App\Infrastructure\Categorias\PdoCategoriasRepository;
use App\Infrastructure\Common\Database;
use App\Infrastructure\Gastos\PdoGastosRepository;
use App\Infrastructure\Personas\PdoPersonasRepository;
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
        return new CreateGastoCommandHandler(
            $container->get(PdoGastosRepository::class),
            $container->get(PdoCategoriasRepository::class),
            $container->get(PdoPersonasRepository::class)
        );
    },
    UpdateGastoCommandHandler::class => function (Container $container) {
        return new UpdateGastoCommandHandler(
            $container->get(PdoGastosRepository::class),
            $container->get(PdoCategoriasRepository::class),
            $container->get(PdoPersonasRepository::class)
        );
    },
    DeleteGastoCommandHandler::class => function (Container $container) {
        return new DeleteGastoCommandHandler($container->get(PdoGastosRepository::class));
    },
    GetGastosByPersonaQueryHandler::class => function (Container $container) {
        return new GetGastosByPersonaQueryHandler(
                $container->get(PdoGastosRepository::class),
                $container->get(PdoPersonasRepository::class),
                new GAstosPresenter()
            );
    },
    GetGastosByCategoriaPersonaPeriodoQueryHandler::class => function (Container $container) {
        return new GetGastosByCategoriaPersonaPeriodoQueryHandler(
            $container->get(PdoGastosRepository::class),
            new GastoCategoriaPresenter()
        );
    }
];