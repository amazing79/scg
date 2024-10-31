<?php

namespace App\Domain\Categorias\Exceptions;

class NotFoundCategoryException extends \Exception
{
    public function __construct(
        string $message = 'Categoria no encontrada.',
        int $code = 404,
        \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}