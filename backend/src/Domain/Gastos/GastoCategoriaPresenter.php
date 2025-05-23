<?php

namespace App\Domain\Gastos;

use App\Domain\Common\Presenter;

class GastoCategoriaPresenter implements Presenter
{

    public function convert($data): array
    {
        /**
         * @var GastoReporte $data
         */
        return [
            'idPersona' => $data->getIdPersona(),
            'persona' => $data->getPersona(),
            'idCategoria' => $data->getIdCategoria(),
            'descripcion' => $data->getDescripcion(),
            'total' => $data->getTotal(),
        ];
    }
}