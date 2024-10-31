<?php

namespace App\Application\Categorias;

use App\Domain\Categorias\Categoria;
use App\Domain\Categorias\CategoriasRepository;
use App\Domain\Common\Conts\HttpStatusCode;
use App\Domain\Common\Conts\HttpStatusMessages;

class CreateCategoriaCommandHandler
{
    private CategoriasRepository $repository;

    public function __construct(CategoriasRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(array $values): array
    {
        $response = [];
        $response['code'] = HttpStatusCode::INTERNAL_SERVER_ERROR;
        $response['message'] = HttpStatusMessages::getMessage(HttpStatusCode::INTERNAL_SERVER_ERROR);
        try {
            $categoria = Categoria::createFromArray($values);
            $idCategoria = $this->repository->create($categoria);
            $response['code'] = HttpStatusCode::CREATED;
            $response['message'] = "Se ha añadido la categoría con exito con el siguiente id: {$idCategoria}";
            $response['data'] = $idCategoria;
        } catch (\Exception $e) {
            $response['message'] = "Code error: {$e->getCode()} - descripcion: {$e->getMessage()}";
        }
        return $response;
    }
}