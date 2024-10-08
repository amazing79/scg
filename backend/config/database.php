<?php

use App\Infrastructure\Common\Database;

return [
    Database::class => function () {
        return new Database(
            $_ENV['DB_HOST'],
            $_ENV['DB_NAME'],
            $_ENV['DB_USER'],
            $_ENV['DB_PASS']
        );
    }
];