<?php

namespace App\Domain\Personas\Exceptions;

use App\Domain\Common\Conts\HttpStatusCode;

class PersonaNotFoundException extends \Exception
{
    public function __construct(
        string $message = 'Persona no encontrada.',
        int $code = HttpStatusCode::NOT_FOUND,
        \Exception $previous = null
    )
    {
        parent::__construct($message, $code, $previous);
    }
}