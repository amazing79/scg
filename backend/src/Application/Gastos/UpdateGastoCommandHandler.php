<?php

namespace App\Application\Gastos;

use App\Domain\Common\Traits\EnsureObjectExists;
use App\Domain\Gastos\Exceptions\GastoNotFoundExceptions;
use App\Domain\Gastos\Gastos;
use App\Domain\Gastos\GastosRepository;

class UpdateGastoCommandHandler
{
    use EnsureObjectExists;

    public function __construct(
        private GastosRepository $repository
    )
    {
    }

    public function handle($values): array
    {
        $response = [];
        try{
            $id = $values['id'];
            $this->assertObjectExist(
                $id,
                $this->repository,
                new GastoNotFoundExceptions());
            $gasto = Gastos::fromArray($values);
            $this->repository->update($gasto);
            $response['code'] = 200;
            $response['message'] = 'Gasto actualizado correctamente';
        } catch (GastoNotFoundExceptions $e) {
            $response['code'] = $e->getCode();
            $response['message'] = $e->getMessage();
        } catch (\Exception $e) {
            $response['code'] = 500;
            $response['message'] = "Code error: {$e->getCode()} - descripcion: {$e->getMessage()}";
        } finally {
            return $response;
        }
    }
}