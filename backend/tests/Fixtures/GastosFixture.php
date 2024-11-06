<?php

namespace Tests\Fixtures;

use App\Domain\Gastos\Gastos;
use App\Infrastructure\Gastos\InMemoryGastosRepository;

class GastosFixture extends InMemoryGastosRepository
{
    private array $gastos = [];

    public function __construct()
    {
        parent::__construct();
        $this->gastos = [
            1 => new Gastos(1, 'Luz', new \DateTimeImmutable('2024-10-01'), 100, 1, 1, ''),
            2 => new Gastos(2, 'Gas', new \DateTimeImmutable('2024-10-01'), 200, 1, 1, ''),
            3 => new Gastos(3, 'Combustible', new \DateTimeImmutable('2024-10-01'), 3000, 2, 1, ''),
            4 => new Gastos(4, 'Varios', new \DateTimeImmutable('2024-10-01'), 3000, 3, 1, ''),
            5 => new Gastos(5, 'Gas', new \DateTimeImmutable('2024-10-01'), 3000, 1, 2, ''),
            6 => new Gastos(5, 'Patente', new \DateTimeImmutable('2024-10-01'), 3000, 1, 2, ''),
        ];
        $this->initialize();
    }

    private function initialize(): void
    {
        foreach ($this->gastos as $gasto) {
            $this->create($gasto);
        }
    }
}