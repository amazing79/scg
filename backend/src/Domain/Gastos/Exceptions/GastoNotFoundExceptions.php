<?php

namespace App\Domain\Gastos\Exceptions;

use App\Domain\Common\Conts\HttpStatusCode;
use Throwable;

class GastoNotFoundExceptions extends \Exception
{
    public function __construct(
        $message = "Gasto no Encontrado",
        $code = HttpStatusCode::NOT_FOUND,
        Throwable $previous = null
    )
    {
        parent::__construct($message, $code, $previous);
    }
}