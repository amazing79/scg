<?php

namespace App\Infrastructure\Gastos;

use App\Domain\Gastos\Gastos;
use App\Domain\Gastos\GastosRepository;
use App\Infrastructure\Common\MemoryDB;

class InMemoryGastosRepository implements GastosRepository
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
         * @var Gastos $data
         */
        $this->db->set($data->getIdGasto(), $data);
    }

    public function delete($id): void
    {
        $this->db->remove($id);
    }

    public function findById($id): ?Gastos
    {
        return $this->db->get($id);
    }

    public function getAll(): array
    {
        return $this->db->getAll();
    }

    public function find($filter): array
    {
        return $this->db->find($filter, 'getDescripcion');
    }

    public function getGastosByPersona(int $personaId, $periodo = null): array
    {
        return $this->db->find($personaId, 'getPersona');
    }
}