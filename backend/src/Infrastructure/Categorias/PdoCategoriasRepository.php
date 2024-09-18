<?php

namespace App\Infrastructure\Categorias;

use App\Domain\Categorias\Categoria;
use App\Domain\Categorias\CategoriasRepository;
use App\Infrastructure\Common\Database;
use PDO;

class PdoCategoriasRepository implements CategoriasRepository
{
    private $db;

    /**
     * @param Database $db
     */
    public function __construct(Database $db)
    {
        $this->db = $db;
    }


    public function create($data)
    {
        /**
         * @var Categoria $data;
         */
        $sql = 'INSERT INTO categorias (descripcion) VALUES (:descripcion)';

        $pdo = $this->db->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':descripcion', $data->getDescripcion(), PDO::PARAM_STR);

        $stmt->execute();

        return $pdo->lastInsertId();
    }

    public function update($data): int
    {
        /**
         * @var Categoria $data
         */
        $sql = 'UPDATE categorias SET descripcion = :descripcion WHERE idCategoria = :id';

        $pdo = $this->db->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':descripcion', $data->getDescripcion(), PDO::PARAM_STR);
        $stmt->bindValue(':id', $data->getId(), PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount();
    }

    public function delete($id)
    {
        $method = __METHOD__;
        throw new \Exception("el metodo {$method} aún no ha sido implementado");
    }

    public function findById($id)
    {
        $sql = 'SELECT idCategoria, descripcion FROM categorias WHERE idCategoria = :id';
        $pdo = $this->db->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll()
    {
        $pdo = $this->db->getConnection();
        $stmt = $pdo->query('Select idCategoria, descripcion from categorias');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}