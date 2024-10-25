<?php

namespace App\Application\Gastos;

use App\Domain\Gastos\Gastos;
use App\Domain\Gastos\GastosRepository;

class CreateGastoCommandHandler
{
    public function __construct(
        private GastosRepository $repository
    )
    {

    }

    public function handle($values): array
    {
        $response = [];
        $response['code'] = 500;
        $response['message'] = 'Error inesperado, por favor intente de nuevo';
        try{
            $gasto = Gastos::fromArray($values);
            $idGasto = $this->repository->create($gasto);
            $response['data'] = $idGasto;
            $response['code'] = 201;
            $response['message'] = "Gasto registrado correctamente con el id {$idGasto}";
        } catch (\Exception $e) {

            $response['message'] = "Code error: {$e->getCode()} - descripcion: {$e->getMessage()}";
        } finally {
            return $response;
        }
    }
}