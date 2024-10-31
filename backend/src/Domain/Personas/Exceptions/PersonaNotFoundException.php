<?php

namespace App\Domain\Personas\Exceptions;

class PersonaNotFoundException extends \Exception
{
    public function __construct(
        string $message = 'Persona no encontrada.',
        int $code = 404,
        \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}