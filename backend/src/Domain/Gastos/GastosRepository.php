<?php

namespace App\Domain\Gastos;

use App\Domain\Common\Base;

interface GastosRepository extends Base
{
    public function getGastosByPersona(int $personaId, $periodo = null): array;
    public function getGastosByCategoriaPersonaPeriodo($periodo = null);
    public function getTotalGastosPersonaInPeriodo($periodo);
}