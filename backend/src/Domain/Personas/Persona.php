<?php

namespace App\Domain\Personas;

class Persona
{
    private int $id;
    private string $nombre;
    private string $apellido;
    private ?string $apodo;

    public function __construct(int $id, string $nombre, string $apellido, ?string $apodo = null)
    {
        $this->id = $id;
        $this->assertValidApellido($apellido);
        $this->assertValidNombre($nombre);
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->apodo = $apodo;
    }

    public function getId(): int
    {
        return $this->id;
    }


    /**
     * @return string
     */
    public function getNombre(): string
    {
        return $this->nombre;
    }

    /**
     * @return string
     */
    public function getApellido(): string
    {
        return $this->apellido;
    }

    /**
     * @return string|null
     */
    public function getApodo(): ?string
    {
        return $this->apodo;
    }

    public function getApellidoNombre(): string
    {
        return "{$this->apellido}, {$this->nombre}";
    }

    public static function createFromArray(array $data): self
    {
        $id = $data['idPersona'] ?? $data['id'] ?? 0;
        $nombre = $data['nombre'] ?? '';
        $apellido = $data['apellido'] ?? '';
        $apodo = $data['apodo'] ?? '';
        return new self($id, $nombre, $apellido, $apodo);
    }

    private function assertValidApellido(string $apellido): void
    {
        if(trim($apellido) == '') {
            throw new \InvalidArgumentException('Debe indicar el apellido de la persona');
        }
    }

    private function assertValidNombre(string $nombre): void
    {
        if(trim($nombre) == '') {
            throw new \InvalidArgumentException('Debe indicar el nombre de la persona');
        }
    }
}