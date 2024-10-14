<?php

namespace App\Application\Personas;

use App\Domain\Personas\Persona;
use App\Domain\Personas\PersonasRepository;

class CreatePersonaCommandHandler
{
    private PersonasRepository $repository;

    public function __construct(PersonasRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(array $values): array
    {
        try {
            $response = [];
            $persona = Persona::createFromArray($values);
            $idPersona = $this->repository->create($persona);
            $response['code'] = 201;
            $response['message'] = "Se ha añadido la persona con exito con el siguiente id: {$idPersona}";
            $response['data'] = $idPersona;
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();
            $response['code'] = 500;
        }
        return $response;
    }
}