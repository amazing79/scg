<?php

namespace App\Infrastructure\Categorias;

use App\Domain\Categorias\CategoriasRepository;

class InMemoryCategoriasRepository implements CategoriasRepository
{


    public function __construct(protected array $memory = [])
    {

    }

    public function create($data)
    {
        $method = __METHOD__;
        throw new \Exception("el metodo {$method} aún no ha sido implementado");
    }

    public function update($data)
    {
        $method = __METHOD__;
        throw new \Exception("el metodo {$method} aún no ha sido implementado");
    }

    public function delete($id)
    {
        $method = __METHOD__;
        throw new \Exception("el metodo {$method} aún no ha sido implementado");
    }

    public function findById($id)
    {
        $method = __METHOD__;
        throw new \Exception("el metodo {$method} aún no ha sido implementado");
    }

    public function getAll(): array
    {
        return $this->memory;
    }
}