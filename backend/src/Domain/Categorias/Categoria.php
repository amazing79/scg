<?php

namespace App\Domain\Categorias;

class Categoria
{
    private $id;
    private string $descripcion;

    /**
     * @param $id
     * @param string $descripcion
     */
    public function __construct($id, string $descripcion)
    {
        $this->assertValidDescription($descripcion);
        $this->id = $id;
        $this->descripcion = $descripcion;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    public static function createFromArray($values): Categoria
    {
        $id = $values['idCategoria'] ?? $values['id'] ?? 0;
        $descripcion = $values['descripcionCategoria'] ?? $values['descripcion'] ?? '';
        return new self(
            $id,
            $descripcion
        );
    }

    private function assertValidDescription($descripcion): void
    {
        if (!is_string($descripcion) || !strlen(trim($descripcion))) {
            throw new \InvalidArgumentException('Debe ingresar una descripción.');
        }
    }
}
