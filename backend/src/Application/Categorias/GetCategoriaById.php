<?php

namespace App\Application\Categorias;

use App\Domain\Categorias\CategoriasRepository;
use App\Domain\Categorias\Exceptions\NotFoundCategoryException;
use App\Domain\Common\Presenter;

class GetCategoriaById
{
    use EnsureExistCategoria;
    private CategoriasRepository $repository;
    private ?Presenter $presenter;

    public function __construct(CategoriasRepository $repository, Presenter $presenter = null)
    {
        $this->repository = $repository;
        $this->presenter = $presenter;
    }

    private function hasPresenter(): bool
    {
        return !is_null($this->presenter);
    }

    public function handle(int $id): array
    {
        try {
            $this->assertExistCategoria($id, $this->repository);
            $categoria = $this->repository->findById($id);
            if($this->hasPresenter()) {
                $categoria = $this->presenter->convert($categoria);
            }
            $response = [];
            $response['code'] = 200;
            $response['data'] = $categoria;
            $response['message'] = 'Categoria obtenida con exito!';
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