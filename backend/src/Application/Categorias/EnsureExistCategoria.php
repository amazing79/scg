<?php

namespace App\Application\Categorias;

use App\Domain\Categorias\CategoriasRepository;
use App\Domain\Categorias\Exceptions\NotFoundCategoryException;

trait EnsureExistCategoria
{
    public function assertExistCategoria($idCategoria, CategoriasRepository $repository)
    {
        $categoria = $repository->findById($idCategoria);
        if(!$categoria) {
            throw new NotFoundCategoryException('No existe la categoria solicitada', 404);
        }
    }
}