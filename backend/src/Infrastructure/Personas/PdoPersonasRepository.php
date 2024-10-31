<?php

namespace App\Infrastructure\Personas;

use App\Domain\Personas\Persona;
use App\Domain\Personas\PersonasRepository;
use App\Infrastructure\Common\Database;
use PDO;

class PdoPersonasRepository implements PersonasRepository
{
    private Database $db;

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
         * @var Persona $data;
         */
        $sql = 'INSERT INTO persona (apellido, nombre, apodo) VALUES (:apellido, :nombre, :apodo)';

        $pdo = $this->db->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':apellido', $data->getApellido());
        $stmt->bindValue(':nombre', $data->getNombre());
        $stmt->bindValue(':apodo', $data->getApodo());

        $stmt->execute();

        return $pdo->lastInsertId();
    }

    public function update($data)
    {
        /**
         * @var Persona $data
         */
        $sql = 'UPDATE persona SET apellido = :apellido, nombre = :nombre, apodo = :apodo WHERE idPersona = :id';

        $pdo = $this->db->getConnection();
        $stmt = $pdo->prepare($sql);
        //al ser string se omite el type de parametro, ya que es el valor por defecto PDO::PARAM_STR
        $stmt->bindValue(':apellido', $data->getApellido() );
        $stmt->bindValue(':nombre', $data->getNombre() );
        $stmt->bindValue(':apodo', $data->getApodo() );
        $stmt->bindValue(':id', $data->getId(), PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount();
    }

    public function delete($id)
    {
        $sql = 'DELETE FROM persona WHERE idPersona = :id';

        $pdo = $this->db->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount();
    }

    public function findById($id)
    {
        $sql = 'SELECT idPersona, apellido, nombre, apodo FROM persona WHERE idPersona = :id';
        $pdo = $this->db->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll()
    {
        $pdo = $this->db->getConnection();
        $stmt = $pdo->query('Select idPersona, apellido, nombre, apodo from persona');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($filter)
    {
        $method = __METHOD__;
        throw new \Exception("Metodo ${method} aún no ha sido implementado");
    }
}