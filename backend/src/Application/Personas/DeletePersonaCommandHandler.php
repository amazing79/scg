<?php

namespace App\Application\Personas;

use App\Domain\Common\Traits\EnsureObjectExists;
use App\Domain\Personas\Exceptions\PersonaNotFoundException;
use App\Domain\Personas\PersonasRepository;

class DeletePersonaCommandHandler
{
    use EnsureObjectExists;
    private PersonasRepository $repository;

    public function __construct(PersonasRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(int $id): array
    {
        try {
            $response = [];
            $this->assertObjectExist(
                $id,
                $this->repository,
                new PersonaNotFoundException());
            $this->repository->delete($id);
            $response['code'] = 200;
            $response['message'] = "Persona borrada correctamente";
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
