<?php

namespace App\Infrastructure\Common;

class MemoryDB
{
    public function __construct(private array $db = []) {}

    public function get(int $key)
    {
        return $this->db[$key] ?? null;
    }

    public function set(int $key, $value): void
    {
        $this->db[$key] = $value;
    }

    public function getAll(): array
    {
        return $this->db;
    }

    public function add($value): int
    {
        $keys = count($this->db);
        $this->db[$keys] = $value;
        return $keys;
    }

    public function remove(int $key): void
    {
        unset($this->db[$key]);
    }

    public function clear(): void
    {
        $this->db = [];
    }

    public function find($value, $field): array
    {
        $matches = [];
        foreach ($this->db as $key => $data) {
            if ($data[$field] === $value) {
                $matches[$key] = $data;
            }
        }
        return $matches;
    }
}