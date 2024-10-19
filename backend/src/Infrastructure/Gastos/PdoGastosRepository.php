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
        /**
         * @var Gastos $data
         */
        $sql = 'UPDATE gastos 
                SET fecha_gasto = :fecha, descripcion = :descripcion, monto = :monto,
                    categoria = :categoria, persona = :persona, observaciones = :observaciones
                WHERE idGasto = :id';

        $pdo = $this->db->getConnection();
        $stmt = $pdo->prepare($sql);
        //al ser string se omite el type de parametro, ya que es el valor por defecto PDO::PARAM_STR
        $stmt->bindValue(':fecha', $data->getFecha());
        $stmt->bindValue(':descripcion', $data->getDescripcion());
        $stmt->bindValue(':monto', $data->getMonto());
        $stmt->bindValue(':categoria', $data->getCategoria(), PDO::PARAM_INT);
        $stmt->bindValue(':persona', $data->getPersona(), PDO::PARAM_INT);
        $stmt->bindValue(':observaciones', $data->getObservaciones());
        $stmt->bindValue(':id', $data->getIdPago(), PDO::PARAM_INT);


        $stmt->execute();

        return $stmt->rowCount();
    }

    public function delete($id)
    {
        $sql = 'DELETE FROM gastos WHERE idGasto = :id';

        $pdo = $this->db->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount();
    }

    public function findById($id)
    {
        $sql = 'SELECT 
                 idGasto, fecha_gasto, descripcion, monto, categoria, persona, observaciones
                FROM gastos 
                WHERE idGasto = :id';
        $pdo = $this->db->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
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