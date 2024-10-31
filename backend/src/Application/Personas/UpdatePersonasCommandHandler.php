<?php

namespace App\Application\Personas;

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
        try {
            $response = [];
            $id = $values['id'] ?? 0;
            $this->assertObjectExist(
                $id,
                $this->repository,
                new PersonaNotFoundException());
            $persona = Persona::createFromArray($values);
            $this->repository->update($persona);
            $response['code'] = 200;
            $response['message'] = "Se ha actualizado la persona con exito!";
        } catch (PersonaNotFoundException $e) {
            $response['message'] = $e->getMessage();
            $response['code'] = $e->getCode();
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();
            $response['code'] = 500;
        }
        return $response;
    }
}