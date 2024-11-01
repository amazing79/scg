<?php

namespace App\Application\Personas;

use App\Domain\Common\Conts\HttpStatusCode;
use App\Domain\Common\Conts\HttpStatusMessages;
use App\Domain\Common\Presenter;
use App\Domain\Personas\PersonasRepository;

class GetPersonasQueryHandler
{
    private PersonasRepository $repository;
    private ?Presenter $presenter;

    public function __construct(PersonasRepository $repository, Presenter $presenter = null)
    {
        $this->repository = $repository;
        $this->presenter = $presenter;
    }

    private function hasPresenter():bool
    {
        return $this->presenter !== null;
    }

    public function handle(): array
    {
        $response = [];
        $response['code'] = HttpStatusCode::INTERNAL_SERVER_ERROR;
        $response['message'] = HttpStatusMessages::getMessage(HttpStatusCode::INTERNAL_SERVER_ERROR);
        try {
            $personas = $this->repository->getAll();
            if($this->hasPresenter()) {
                $personas = $this->converDataWithPresenter($personas);
            }
            $response['code'] = HttpStatusCode::OK;
            $response['message'] = "Listado de personas obtenidos correctamente";
            $response['data'] = $personas;
            $response['totalFound'] = count($personas);
        } catch (\Exception $e) {
            $response['message'] = "Code error: {$e->getCode()} - descripcion: {$e->getMessage()}";
        }
        return $response;
    }

    private function converDataWithPresenter($personas): array
    {
        $converted = [];
        foreach ($personas as $persona) {
            $converted[] = $this->presenter->convert($persona);
        }
        return $converted;
    }
}