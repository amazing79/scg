<?php

namespace App\Application\Personas;

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
        try {
            $response = [];
            $personas = $this->repository->getAll();
            if($this->hasPresenter()) {
                $personas = $this->converDataWithPresenter($personas);
            }
            $response['code'] = 200;
            $response['message'] = "Listado de personas obtenidos correctamente";
            $response['data'] = $personas;
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();
            $response['code'] = 500;
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