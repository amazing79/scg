<?php

namespace App\Domain\Gastos;

use DateTimeImmutable;

class Gastos
{

    public function __construct(
        private int $idPago,
        private string $descripcion,
        private \DateTimeImmutable $fecha,
        private float $monto,
        private int $categoria,
        private int $persona,
        private string $observaciones
    )
    {
    }

    public function getIdPago(): int
    {
        return $this->idPago;
    }

    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    public function getFecha(): \DateTimeImmutable
    {
        return $this->fecha;
    }

    public function getMonto(): float
    {
        return $this->monto;
    }

    public function getCategoria(): int
    {
        return $this->categoria;
    }

    public function getPersona(): int
    {
        return $this->persona;
    }

    public function getObservaciones(): string
    {
        return $this->observaciones;
    }

    public static function fromArray(array $array): Gastos
    {
        $idPago = $array['idPago'] ?? 0;
        $descripcion = $array['descripcion'] ?? '';
        $fecha = $array['fecha'] ?? new DateTimeImmutable('now');
        $monto = $array['monto'] ?? 0.0;
        $categoria = $array['categoria'] ?? 0;
        $persona = $array['persona'] ?? 0;
        $observaciones = $array['observaciones'] ?? '';
        return new self(
            $idPago,
            $descripcion,
            $fecha,
            $monto,
            $categoria,
            $persona,
            $observaciones
        );
    }
}