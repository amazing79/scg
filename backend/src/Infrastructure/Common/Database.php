<?php

namespace App\Infrastructure\Common;

use PDO;
class Database
{
    private string $host;
    private string $db;
    private string $user;
    private string $password;

    public function __construct(
        string $host,
        string $db,
        string $user,
        string $password
    )
    {
        $this->host =  $host;
        $this->db =  $db ;
        $this->user = $user;
        $this->password = $password;
    }

    public function getConnection():PDO
    {
        $dsn = "mysql:host=$this->host;dbname=$this->db;charset=utf8";
        return new PDO($dsn, $this->user, $this->password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }
}