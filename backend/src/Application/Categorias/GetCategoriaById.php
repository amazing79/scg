<?php

namespace App\Application\Categorias;

use App\Domain\Categorias\CategoriasRepository;
use App\Domain\Common\Presenter;

class GetCategoriaById
{
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
            $categoria = $this->repository->findById($id);
            $this->postAssertExistsCategoria($categoria);
            if($this->hasPresenter()) {
                $categoria = $this->presenter->convert($categoria);
            }
            $response = [];
            $response['code'] = 200;
            $response['data'] = $categoria;
            $response['message'] = 'Categorias obtenida con exito!';
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();
            $response['code'] = 500;
        }
        return $response;
    }

    private function postAssertExistsCategoria($categoria)
    {
        if(is_null($categoria) || $categoria === false) {
            throw new \Exception('La categoria solicitada no existe!');
        }
    }
}