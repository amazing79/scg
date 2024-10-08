<?php

namespace App\Application\Categorias;

use App\Domain\Categorias\CategoriasRepository;
use App\Domain\Common\Presenter;

class GetCategoriasQueryHandler
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

    public function handle(): array
    {
        try {
            $categorias = $this->repository->getAll();
            if($this->hasPresenter()) {
                $categorias = $this->convertDataWithPresenter($categorias);
            }
            $response = [];
            $response['code'] = 200;
            $response['data'] = $categorias;
            $response['message'] = 'Categorias obtenidas con exito!';
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();
            $response['code'] = 500;
        }
        return $response;
    }

    private function convertDataWithPresenter($categorias): array
    {
        $converted = [];
        foreach ($categorias as $categoria) {
            $converted[] = $this->presenter->convert($categoria);
        }
        return $converted;
    }
}