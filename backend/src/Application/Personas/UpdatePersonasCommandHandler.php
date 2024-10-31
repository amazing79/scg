<?php

namespace App\Application\Personas;

use App\Domain\Common\Conts\HttpStatusCode;
use App\Domain\Common\Conts\HttpStatusMessages;
use App\Domain\Common\Traits\EnsureObjectExists;
use App\Domain\Personas\Exceptions\PersonaNotFoundException;
use App\Domain\Personas\Persona;
use App\Domain\Personas\PersonasRepository;

class UpdatePersonasCommandHandler
{
    use EnsureObjectExists;
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
            $id = $values['id'] ?? 0;
            $this->assertObjectExist(
                $id,
                $this->repository,
                new PersonaNotFoundException());
            $persona = Persona::createFromArray($values);
            $this->repository->update($persona);
            $response['code'] = HttpStatusCode::OK;
            $response['message'] = "Se ha actualizado la persona con exito!";
        } catch (PersonaNotFoundException $e) {
            $response['message'] = $e->getMessage();
            $response['code'] = $e->getCode();
        } catch (\Exception $e) {
            $response['message'] = "Code error: {$e->getCode()} - descripcion: {$e->getMessage()}";
        }
        return $response;
    }
}