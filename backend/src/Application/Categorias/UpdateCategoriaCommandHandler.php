<?php

namespace App\Application\Categorias;

use App\Domain\Categorias\Categoria;
use App\Domain\Categorias\CategoriasRepository;
use App\Domain\Categorias\Exceptions\NotFoundCategoryException;

class UpdateCategoriaCommandHandler
{
    use EnsureExistCategoria;
    private CategoriasRepository $repository;

    public function __construct(CategoriasRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(array $values): array
    {
        try {
            $response = [];
            $this->assertExistCategoria($values['id'], $this->repository);
            $categoria = Categoria::createFromArray($values);
            $this->repository->update($categoria);
            $response['code'] = 200;
            $response['message'] = "la categoría se ha actualizado correctamente";
        } catch (NotFoundCategoryException $e) {
            $response['message'] = $e->getMessage();
            $response['code'] = $e->getCode();
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();
            $response['code'] = 500;
        }
        return $response;
    }
}