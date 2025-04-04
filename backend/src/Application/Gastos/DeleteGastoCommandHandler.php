<?php

namespace App\Application\Gastos;

use App\Domain\Common\Conts\HttpStatusCode;
use App\Domain\Common\Conts\HttpStatusMessages;
use App\Domain\Common\Traits\EnsureObjectExists;
use App\Domain\Gastos\Exceptions\GastoNotFoundExceptions;
use App\Domain\Gastos\GastosRepository;

class DeleteGastoCommandHandler
{
    use EnsureObjectExists;

    public function __construct(
        private GastosRepository $repository
    )
    {

    }

    public function handle($id): array
    {
        $response = [];
        $response['code'] = HttpStatusCode::INTERNAL_SERVER_ERROR;
        $response['message'] = HttpStatusMessages::getMessage(HttpStatusCode::INTERNAL_SERVER_ERROR);
        try{
            $this->assertObjectExist(
                $id,
                $this->repository,
                new GastoNotFoundExceptions());
            $this->repository->delete($id);
            $response['code'] = HttpStatusCode::OK;
            $response['message'] = 'Gasto eliminado correctamente';
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