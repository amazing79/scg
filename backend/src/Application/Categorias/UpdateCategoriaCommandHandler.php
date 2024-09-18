<?php

namespace App\Application\Categorias;

use App\Domain\Categorias\Categoria;
use App\Domain\Categorias\CategoriasRepository;

class UpdateCategoriaCommandHandler
{
    private CategoriasRepository $repository;

    public function __construct(CategoriasRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(array $values): array
    {
        try {
            $response = [];
            $categoria = Categoria::createFromArray($values);
            $this->repository->update($categoria);
            $response['code'] = 200;
            $response['message'] = "la categoría se ha actualizado correctamente";
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();
            $response['code'] = 500;
        }
        return $response;
    }
}