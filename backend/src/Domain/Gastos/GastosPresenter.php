<?php

namespace App\Domain\Gastos;

use App\Domain\Common\Presenter;

class GastosPresenter implements Presenter
{

    public function convert($data)
    {
        /**
         * @var GastoDetalle $data
         */
        return [
            'idGasto' => $data->getGastos()->getIdGasto(),
            'fecha' => $data->getGastos()->getFecha(),
            'descripcion' => $data->getGastos()->getDescripcion(),
            'monto' => number_format($data->getGastos()->getMonto(), 2),
            'categoria' => $data->getCategoria()->getId(),
            'datosCategoria' => $data->getCategoria()->getDescripcion(),
            'persona' => $data->getPersona()->getId(),
            'datosPersona' => $data->getPersona()->getApellidoNombre(),
            'observaciones' => $data->getGastos()->getObservaciones(),
        ];
    }
}