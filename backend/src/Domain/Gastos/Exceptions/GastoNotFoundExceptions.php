<?php

namespace App\Domain\Gastos\Exceptions;

use Throwable;

class GastoNotFoundExceptions extends \Exception
{
    public function __construct($message = "Gasto no Encontrado", $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}