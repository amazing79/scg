<?php

namespace App\Application\Personas;

use App\Domain\Common\Conts\HttpStatusCode;
use App\Domain\Common\Conts\HttpStatusMessages;
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
        $response = [];
        $response['code'] = HttpStatusCode::INTERNAL_SERVER_ERROR;
        $response['message'] = HttpStatusMessages::getMessage(HttpStatusCode::INTERNAL_SERVER_ERROR);
        try {
            $this->assertObjectExist(
                $id,
                $this->repository,
                new PersonaNotFoundException());
            $persona = $this->repository->findById($id);
            if($this->hasPresenter()) {
                $persona = $this->presenter->convert($persona);
            }
            $response['code'] = HttpStatusCode::OK;
            $response['message'] = "Persona obtenida correctamente";
            $response['data'] = $persona;
        } catch (PersonaNotFoundException $e) {
            $response['message'] = $e->getMessage();
            $response['code'] = $e->getCode();
        } catch (\Exception $e) {
            $response['message'] = "Code error: {$e->getCode()} - descripcion: {$e->getMessage()}";
        }
        return $response;
    }
}
