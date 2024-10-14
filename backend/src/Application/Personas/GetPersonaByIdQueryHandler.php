<?php

namespace App\Application\Personas;

use App\Domain\Common\Presenter;
use App\Domain\Common\Traits\EnsureObjectExists;
use App\Domain\Personas\Exceptions\PersonaNotFoundException;
use App\Domain\Personas\PersonasRepository;

class GetPersonaByIdQueryHandler
{
    use EnsureObjectExists;
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

    public function handle(int $id): array
    {
        try {
            $response = [];
            $this->assertObjectExist(
                $id,
                $this->repository,
                new PersonaNotFoundException('Persona no encontrada', 404));
            $persona = $this->repository->findById($id);
            if($this->hasPresenter()) {
                $persona = $this->presenter->convert($persona);
            }
            $response['code'] = 200;
            $response['message'] = "Persona obtenida correctamente";
            $response['data'] = $persona;
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
