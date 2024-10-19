<?php

namespace App\Infrastructure\Gastos;

use App\Domain\Gastos\Gastos;
use App\Domain\Gastos\GastosRepository;
use App\Infrastructure\Common\Database;
use PDO;

class PdoGastosRepository implements GastosRepository
{

    public function __construct(private Database $db)
    {

    }

    public function create($data)
    {
        /**
         * @var Gastos $data;
         */
        $sql = 'INSERT INTO gastos (fecha_gasto, descripcion, monto, categoria, persona, observaciones) 
                VALUES (:fecha, :descripcion, :monto, :categoria, :persona, :observaciones)';

        $pdo = $this->db->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':fecha', $data->getFecha());
        $stmt->bindValue(':descripcion', $data->getDescripcion());
        $stmt->bindValue(':monto', $data->getMonto());
        $stmt->bindValue(':categoria', $data->getCategoria(), PDO::PARAM_INT);
        $stmt->bindValue(':persona', $data->getPersona(), PDO::PARAM_INT);
        $stmt->bindValue(':observaciones', $data->getObservaciones());

        $stmt->execute();

        return $pdo->lastInsertId();
    }

    public function update($data)
    {
        // TODO: Implement update() method.
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    public function findById($id)
    {
        // TODO: Implement findById() method.
    }

    public function getAll(): bool|array
    {
       $pdo = $this->db->getConnection();
       $stmt = $pdo->query("
                SELECT 
                    idGasto, fecha_gasto, descripcion, monto, categoria, persona, observaciones
                FROM 
                gastos"
       );
       return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}