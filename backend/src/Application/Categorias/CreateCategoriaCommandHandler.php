<?php

namespace App\Application\Categorias;

use App\Domain\Categorias\Categoria;
use App\Domain\Categorias\CategoriasRepository;

class CreateCategoriaCommandHandler
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
            $idCategoria = $this->repository->create($categoria);
            $response['code'] = 200;
            $response['message'] = "Se ha añadido la categoría con exito con el siguiente id: {$idCategoria}";
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();
            $response['code'] = 500;
        }
        return $response;
    }
}