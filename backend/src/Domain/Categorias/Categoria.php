<?php

namespace App\Domain\Categorias;

class Categoria
{
    private $id;
    private $descripcion;

    /**
     * @param $id
     * @param $descripcion
     */
    public function __construct($id, $descripcion)
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
     * @return mixed
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public static function createFromArray($values): Categoria
    {
        $id = $values['id'] ?? 0;
        $descripcion = $values['descripcion'] ?? '';
        return new self(
            $id,
            $descripcion
        );
    }

    private function assertValidDescription($descripcion)
    {
        if (!is_string($descripcion) || !strlen(trim($descripcion))) {
            throw new \InvalidArgumentException('Debe ingresar una descripción.');
        }
    }
}
