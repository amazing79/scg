<?php

namespace App\Application\Gastos;

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
        try{
            $this->assertObjectExist(
                $id,
                $this->repository,
                new GastoNotFoundExceptions());
            $this->repository->delete($id);
            $response['code'] = 200;
            $response['message'] = 'Gasto eliminado correctamente';
        } catch (GastoNotFoundExceptions $e) {
            $response['code'] = $e->getMessage();
            $response['message'] = $e->getMessage();
        } catch (\Exception $e) {
            $response['code'] = 500;
            $response['message'] = "Code error: {$e->getCode()} - descripcion: {$e->getMessage()}";
        } finally {
            return $response;
        }
    }
}