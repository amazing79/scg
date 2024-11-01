<?php

namespace App\Application\Gastos;

use App\Domain\Common\Conts\HttpStatusCode;
use App\Domain\Common\Conts\HttpStatusMessages;
use App\Domain\Common\Presenter;
use App\Domain\Common\Traits\EnsureObjectExists;
use App\Domain\Gastos\GastosRepository;
use App\Domain\Personas\Exceptions\PersonaNotFoundException;
use App\Domain\Personas\PersonasRepository;

class GetGastosByPersonaQueryHandler
{

    use EnsureObjectExists;
    /**
     * @param GastosRepository $repository
     * @param PersonasRepository $personasRepository
     * @param Presenter|null $presenter
     */
    public function __construct(
        private GastosRepository $repository,
        private PersonasRepository $personasRepository,
        private ?Presenter $presenter = null
    )
    {
    }

    public function handle(int $persona): array
    {
        $response = [];
        $response['code'] = HttpStatusCode::INTERNAL_SERVER_ERROR;
        $response['message'] = HttpStatusMessages::getMessage(HttpStatusCode::INTERNAL_SERVER_ERROR);
        try {
            $this->assertObjectExist(
                $persona,
                $this->personasRepository,
                new PersonaNotFoundException()
            );
            $gastos = $this->repository->getGastosByPersona($persona);
            if ($this->hasPresenter()) {
                $gastos = $this->convertDataWithPresenter($gastos);
            }
            $response['code'] = HttpStatusCode::OK;
            $response['data'] = $gastos;
            $response['message'] = 'Gastos obtenidos correctamente';
            $response['totalFound'] = count($gastos);
        } catch (PersonaNotFoundException $e) {
            $response['code'] = HttpStatusCode::BAD_REQUEST;
            $response['message'] = "No se encontro la persona de la cual quiere obtener los gastos";
        } catch (\Exception $e) {
            $response['message'] = "Code error: {$e->getCode()} - descripcion: {$e->getMessage()}";
        } finally {
            return $response;
        }
    }

    private function hasPresenter(): bool
    {
        return isset($this->presenter);
    }

    private function convertDataWithPresenter(array $gastos): array
    {
        $converted = [];
        foreach ($gastos as $gasto) {
            $converted[] = $this->presenter->convert($gasto);
        }
        return $converted;
    }
}