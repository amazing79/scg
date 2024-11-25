<?php

namespace App\Domain\Users\Exceptions;

use App\Domain\Common\Conts\HttpStatusCode;

class UserNotFoundException extends \Exception
{
    public function __construct(
        string $message = 'Usuario No Valido.',
        int $code = HttpStatusCode::NOT_FOUND,
        \Exception $previous = null
    )
    {
        parent::__construct($message, $code, $previous);
    }
}