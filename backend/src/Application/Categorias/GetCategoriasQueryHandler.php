<?php

namespace App\Application\Categorias;

use App\Domain\Categorias\CategoriasRepository;
use App\Domain\Common\Conts\HttpStatusCode;
use App\Domain\Common\Conts\HttpStatusMessages;
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
        $response = [];
        $response['code'] = HttpStatusCode::INTERNAL_SERVER_ERROR;
        $response['message'] = HttpStatusMessages::getMessage(HttpStatusCode::INTERNAL_SERVER_ERROR);
        try {
            $categorias = $this->repository->getAll();
            if($this->hasPresenter()) {
                $categorias = $this->convertDataWithPresenter($categorias);
            }
            $response['code'] = HttpStatusCode::OK;
            $response['data'] = $categorias;
            $response['totalFound'] = count($categorias);
            $response['message'] = 'Categorias obtenidas con exito!';
        } catch (\Exception $e) {
            $response['message'] = "Code error: {$e->getCode()} - descripcion: {$e->getMessage()}";
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