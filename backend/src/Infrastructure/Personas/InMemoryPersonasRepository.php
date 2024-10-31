<?php

namespace App\Infrastructure\Personas;

use App\Domain\Personas\Persona;
use App\Domain\Personas\PersonasRepository;
use App\Infrastructure\Common\MemoryDB;

class InMemoryPersonasRepository implements PersonasRepository
{

    public function __construct(private ?MemoryDB $db = null)
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
         * @var Persona $data
         */
        $this->db->set($data->getId(), $data);
    }

    public function delete($id): void
    {
        $this->db->remove($id);
    }

    public function findById($id): ?Persona
    {
        return $this->db->get($id);
    }

    public function getAll(): array
    {
        return $this->db->getAll();
    }

    public function find($filter): array
    {
        $nombres = $this->db->find($filter, 'getNombre');
        $apellidos = $this->db->find($filter, 'getApellido');
        return array_merge($nombres, $apellidos);
    }
}