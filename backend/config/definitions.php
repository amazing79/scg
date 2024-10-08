<?php

use App\Application\Categorias\GetCategoriasQueryHandler;
use App\Infrastructure\Categorias\PdoCategoriasRepository;
use App\Infrastructure\Common\Database;

return [
    GetCategoriasQueryHandler::class =>  function () {
        return new GetCategoriasQueryHandler(
            new PdoCategoriasRepository(new Database(
                $_ENV['DB_HOST'],
                $_ENV['DB_NAME'],
                $_ENV['DB_USER'],
                $_ENV['DB_PASS']
            )),
            null
        );
    }
];