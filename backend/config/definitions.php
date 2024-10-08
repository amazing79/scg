<?php

use App\Application\Categorias\GetCategoriaById;
use App\Application\Categorias\GetCategoriasQueryHandler;
use App\Infrastructure\Categorias\PdoCategoriasRepository;
use App\Infrastructure\Common\Database;
use DI\Container;

return [
    PdoCategoriasRepository::class => function (Container $container) {
        return new PdoCategoriasRepository($container->get(Database::class));
    },
    GetCategoriasQueryHandler::class =>  function (Container $container) {
        return new GetCategoriasQueryHandler(
            $container->get(PdoCategoriasRepository::class),
            null
        );
    },
    GetCategoriaById::class => function (Container $container) {
        return new GetCategoriaById($container->get(PdoCategoriasRepository::class));
    }
];