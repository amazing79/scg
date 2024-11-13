<?php

namespace App\Domain\Gastos;

use App\Domain\Common\Presenter;


class GastosTotalPresenter implements Presenter
{

    public function convert($data): array
    {
        /**
         * @var GastoReporte $data
         */
        return [
            'idPersona' => $data->getIdPersona(),
            'persona' => $data->getPersona(),
            'total' => $data->getTotal(),
        ];
    }
}