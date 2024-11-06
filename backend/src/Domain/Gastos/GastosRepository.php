<?php

namespace App\Domain\Gastos;

use App\Domain\Common\Base;

interface GastosRepository extends Base
{
    public function getGastosByPersona(int $personaId, $periodo = null): array;
}