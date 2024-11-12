<?php

namespace App\Domain\Gastos;

use App\Domain\Personas\Persona;

class GastoCategoria
{
    public function __construct(
        private int $idPersona,
        private string $persona,
        private int $idCategoria,
        private string $descripcion,
        private string $total
    )
    {
    }

    public function getIdPersona(): int
    {
        return $this->idPersona;
    }

    public function getPersona(): string
    {
        return $this->persona;
    }

    public function getIdCategoria(): int
    {
        return $this->idCategoria;
    }

    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    public function getTotal(): string
    {
        return $this->total;
    }

    public static function fromArray(array $data): self
    {
        $idPersona = $data['idPersona'];
        $persona = $data["persona"];
        $idCategoria = $data["idCategoria"];
        $descripcion = $data["descripcion"];
        $total = $data["total"];
        return new self(
            $idPersona,
            $persona,
            $idCategoria,
            $descripcion,
            $total
        );
    }
}