<?php

namespace App\Domain\Users\Exceptions;

use App\Domain\Common\Conts\HttpStatusCode;

class InvalidCredentialsException extends \Exception
{

    public function __construct(
        string $message = 'el usuario o la contraseña no son correctas.',
        int $code = HttpStatusCode::UNAUTHORIZED,
        \Exception $previous = null
    )
    {
        parent::__construct($message, $code, $previous);
    }
}