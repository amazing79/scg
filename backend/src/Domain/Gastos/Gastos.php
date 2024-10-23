<?php

namespace App\Domain\Gastos;

use DateTimeImmutable;

class Gastos
{

    public function __construct(
        private int $idGasto,
        private string $descripcion,
        private \DateTimeImmutable $fecha,
        private float $monto,
        private int $categoria,
        private int $persona,
        private string $observaciones
    )
    {
    }

    public function getIdGasto(): int
    {
        return $this->idGasto;
    }

    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    public function getFecha($format = 'Y-m-d'): string
    {
        return $this->fecha->format($format);
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
        $idPago = $array['id'] ?? 0;
        $descripcion = $array['descripcion'] ?? '';
        $fecha = isset($array['fecha'])
            ? new DateTimeImmutable($array['fecha'])
            : new DateTimeImmutable('now');
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