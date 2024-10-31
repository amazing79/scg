<?php

namespace App\Application\Personas;

use App\Domain\Common\Conts\HttpStatusCode;
use App\Domain\Common\Conts\HttpStatusMessages;
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
        $response = [];
        $response['code'] = HttpStatusCode::INTERNAL_SERVER_ERROR;
        $response['message'] = HttpStatusMessages::getMessage(HttpStatusCode::INTERNAL_SERVER_ERROR);
        try {
            $persona = Persona::createFromArray($values);
            $idPersona = $this->repository->create($persona);
            $response['code'] = HttpStatusCode::CREATED;
            $response['message'] = "Se ha añadido la persona con exito con el siguiente id: {$idPersona}";
            $response['data'] = $idPersona;
        } catch (\Exception $e) {
            $response['message'] = "Code error: {$e->getCode()} - descripcion: {$e->getMessage()}";
        }
        return $response;
    }
}