<?php

namespace App\Domain\Gastos;


use App\Domain\Personas\Persona;

class GastoDetalle
{
    public function __construct(
        private Gastos $gastos,
        private Persona $persona
    )
    {
    }

    public function getGastos(): Gastos
    {
        return $this->gastos;
    }

    public function getPersona(): Persona
    {
        return $this->persona;
    }
}