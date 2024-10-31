<?php

namespace App\Application\Gastos;

use App\Domain\Common\Conts\HttpStatusCode;
use App\Domain\Common\Conts\HttpStatusMessages;
use App\Domain\Common\Presenter;
use App\Domain\Common\Traits\EnsureObjectExists;
use App\Domain\Gastos\Exceptions\GastoNotFoundExceptions;
use App\Domain\Gastos\GastosRepository;

class GetGastoByIdQueryHandler
{
    use EnsureObjectExists;
    public function __construct(
        private GastosRepository $repository,
        private ?Presenter $presenter = null
    )
    {

    }

    private function hasPresenter(): bool
    {
        return !is_null($this->presenter);
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
                new GastoNotFoundExceptions()
            );
            $gasto = $this->repository->findById($id);
            if ($this->hasPresenter()) {
                $gasto = $this->presenter->convert($gasto);
            }
            $response['code'] = HttpStatusCode::OK;
            $response['data'] = $gasto;
            $response['message'] = 'Gasto obtenido correctamente';
        } catch (GastoNotFoundExceptions $e) {
            $response['code'] = $e->getCode();
            $response['message'] = $e->getMessage();
        } catch (\Exception $e) {
            $response['message'] = "Code error: {$e->getCode()} - descripcion: {$e->getMessage()}";
        } finally {
            return $response;
        }
    }
}