<?php

namespace App\Domain\Categorias\Exceptions;

use App\Domain\Common\Conts\HttpStatusCode;

class NotFoundCategoryException extends \Exception
{
    public function __construct(
        string $message = 'Categoria no encontrada.',
        int $code = HttpStatusCode::NOT_FOUND,
        \Exception $previous = null
    )
    {
        parent::__construct($message, $code, $previous);
    }
}