<?php

namespace Tests\Fixtures;

use App\Domain\Categorias\Categoria;
use App\Infrastructure\Categorias\InMemoryCategoriasRepository;

class CategoriasFixture extends InMemoryCategoriasRepository
{
    private array $categorias = [];
    public function __construct()
    {
        parent::__construct();
        $this->categorias = [
            1 => new Categoria(1, 'Servicios'),
            2 => new Categoria(2, 'Impuestos'),
            3 => new Categoria(3, 'Farmacias'),
            4 => new Categoria(4, 'Almacen'),
            5 => new Categoria(5, 'Combustibles'),
        ];
        $this->initialize();
    }

    private function initialize():void
    {
        foreach ($this->categorias as $categoria) {
            $this->create($categoria);
        }
    }


}