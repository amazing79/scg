<?php

namespace App\Domain\Gastos;

use App\Domain\Common\Presenter;
use App\Domain\Personas\Persona;

class GastosPresenter implements Presenter
{

    public function convert($data)
    {
        /**
         * @var GastoDetalle $data
         */
        return [
            'idGasto' => $data->getGastos()->getIdGasto(),
            'fecha' => $data->getGastos()->getFecha('d/m/Y'),
            'descripcion' => $data->getGastos()->getDescripcion(),
            'monto' => $data->getGastos()->getMonto(),
            'categoria' => $data->getGastos()->getCategoria(),
            'persona' => $data->getPersona()->getId(),
            'datosPersona' => $data->getPersona()->getApellidoNombre(),
            'observaciones' => $data->getGastos()->getObservaciones(),
        ];
    }
}