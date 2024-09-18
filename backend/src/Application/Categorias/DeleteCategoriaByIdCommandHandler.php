<?php

namespace App\Application\Categorias;

use App\Domain\Categorias\CategoriasRepository;

class DeleteCategoriaByIdCommandHandler
{
    private CategoriasRepository $repository;

    public function __construct(CategoriasRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(int $id): array
    {
        try {
            $this->repository->delete($id);
            $response = [];
            $response['code'] = 200;
            $response['message'] = 'Categoria borrada con exito!';
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();
            $response['code'] = 404;
        }
        return $response;
    }
}
