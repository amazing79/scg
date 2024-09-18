<?php

namespace App\Application\Categorias;

use App\Domain\Categorias\CategoriasRepository;
use App\Domain\Categorias\Exceptions\NotFoundCategoryException;

class DeleteCategoriaByIdCommandHandler
{
    use EnsureExistCategoria;
    private CategoriasRepository $repository;

    public function __construct(CategoriasRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(int $id): array
    {
        try {
            $this->assertExistCategoria($id, $this->repository);
            $this->repository->delete($id);
            $response = [];
            $response['code'] = 200;
            $response['message'] = 'Categoria borrada con exito!';
        } catch (NotFoundCategoryException $e) {
            $response['message'] = $e->getMessage();
            $response['code'] = $e->getCode();
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();
            $response['code'] = 404;
        }
        return $response;
    }
}
