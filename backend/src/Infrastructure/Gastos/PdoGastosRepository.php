<?php

namespace App\Infrastructure\Gastos;

use App\Domain\Categorias\Categoria;
use App\Domain\Gastos\GastoReporte;
use App\Domain\Gastos\GastoDetalle;
use App\Domain\Gastos\Gastos;
use App\Domain\Gastos\GastosRepository;
use App\Domain\Personas\Persona;
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
        $stmt->bindValue(':id', $data->getIdGasto(), PDO::PARAM_INT);


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

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if($data) {
            $fecha = new \DateTimeImmutable($data['fecha_gasto']);
            $data['fecha_gasto'] = $fecha->format('Y-m-d');
        }

        return $data;
    }

    public function getAll(): bool|array
    {
        $result = [];
        $pdo = $this->db->getConnection();
        $stmt = $pdo->query("
                SELECT 
                    idGasto as id, fecha_gasto as fecha, gt.descripcion, monto, categoria, persona, observaciones
                    , per.idPersona, per.apellido, per.nombre, per.apodo
                    , cat.idCategoria, cat.descripcion as descripcionCategoria
                FROM 
                gastos gt
                INNER JOIN persona per ON per.idPersona = gt.persona
                INNER JOIN categorias cat on cat.idCategoria = gt.categoria
                ORDER BY 
                    per.apellido, per.nombre, fecha_gasto
                "
       );
       $dbData = $stmt->fetchAll(PDO::FETCH_ASSOC);
       foreach ($dbData as $unGasto) {
           $persona = Persona::createFromArray($unGasto);
           $gasto = Gastos::fromArray($unGasto);
           $categoria = Categoria::createFromArray($unGasto);
           $gastoDetalle = new GastoDetalle($gasto, $persona, $categoria);
           $result[] = $gastoDetalle;
       }
       return $result;
    }

    public function find($filter)
    {
        $method = __METHOD__;
        throw new \Exception("Metodo ${method} aún no ha sido implementado");
    }

    public function getGastosByPersona(int $personaId, $periodo = null): array
    {
        $result = [];

        $condition = !is_null($periodo) ? " and fecha_gasto ${periodo} " : "";

        $pdo = $this->db->getConnection();
        $sql = "
                SELECT 
                    idGasto as id, fecha_gasto as fecha, gt.descripcion, monto, categoria, persona, observaciones
                    , per.idPersona, per.apellido, per.nombre, per.apodo
                    , cat.idCategoria, cat.descripcion as descripcionCategoria
                FROM 
                gastos gt
                INNER JOIN persona per ON per.idPersona = gt.persona
                INNER JOIN categorias cat on cat.idCategoria = gt.categoria
                where gt.persona = :idPersona
                ${condition}
                ORDER BY 
                    fecha_gasto
                ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':idPersona', $personaId, PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($data as $unGasto) {
            $persona = Persona::createFromArray($unGasto);
            $gasto = Gastos::fromArray($unGasto);
            $categoria = Categoria::createFromArray($unGasto);
            $gastoDetalle = new GastoDetalle($gasto, $persona, $categoria);
            $result[] = $gastoDetalle;
        }
        return $result;
    }

    public function getGastosByCategoriaPersonaPeriodo($periodo = null): array
    {
        $result = [];
        $condition = !is_null($periodo) ? " where fecha_gasto ${periodo} " : "";

        $pdo = $this->db->getConnection();
        $sql = "
            select per.idPersona, concat(per.apellido, ', ', per.nombre)  as persona, cat.idCategoria, cat.descripcion, sum(monto) as 'total'
            from gastos gt
            inner join persona per on per.idPersona = gt.persona
            inner join categorias cat on (cat.idCategoria = gt.categoria)
            ${condition}
            group by idPersona, idCategoria
            order by idPersona, idCategoria
            ;
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($data as $unGasto) {
            $result[] = GastoReporte::fromArray($unGasto);;
        }
        return $result;
    }

    public function getTotalGastosPersonaInPeriodo($periodo): array
    {
        $result = [];

        $pdo = $this->db->getConnection();
        $sql = "
           select persona as idPersona, concat(per.apellido, ', ', per.nombre) as apellidoNombre  ,sum(monto) as 'total'
            from gastos gt
            inner join persona per on per.idPersona = gt.persona
            where fecha_gasto ${periodo}
            group by persona
            order by 2
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($data as $unGasto) {
            $values = [];
            $values['idPersona'] = $unGasto['idPersona'];
            $values['persona'] = $unGasto['apellidoNombre'] ;
            $values['idCategoria'] = 0;
            $values['descripcion'] = '';
            $values['total'] = $unGasto['total'];
            $result[] = GastoReporte::fromArray($values);;
        }
        return $result;
    }
}