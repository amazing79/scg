<?php

namespace App\Infrastructure\Categorias;

use App\Domain\Categorias\Categoria;
use App\Domain\Categorias\CategoriasRepository;
use App\Infrastructure\Common\MemoryDB;

class InMemoryCategoriasRepository implements CategoriasRepository
{


    public function __construct(protected ?MemoryDB $db =null)
    {
        $this->db = $this->db ?? new MemoryDB();
    }

    public function create($data): int
    {
        return $this->db->add($data);
    }

    public function update($data): void
    {
        /**
         * @var Categoria $data
         */
       $this->db->set($data->getId(), $data);
    }

    public function delete($id)
    {
        $method = __METHOD__;
        throw new \Exception("el metodo {$method} aún no ha sido implementado");
    }

    public function findById($id)
    {
       return $this->db->get($id);
    }

    public function getAll(): array
    {
        return $this->db->getAll();
    }
}