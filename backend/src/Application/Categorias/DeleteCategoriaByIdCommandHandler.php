<?php

namespace App\Application\Categorias;

use App\Domain\Categorias\CategoriasRepository;
use App\Domain\Categorias\Exceptions\NotFoundCategoryException;
use App\Domain\Common\Traits\EnsureObjectExists;

class DeleteCategoriaByIdCommandHandler
{
    use EnsureObjectExists;
    private CategoriasRepository $repository;

    public function __construct(CategoriasRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(int $id): array
    {
        try {
            $this->assertObjectExist(
                $id,
                $this->repository,
                new NotFoundCategoryException()
            );
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
