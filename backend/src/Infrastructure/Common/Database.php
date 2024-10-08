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

    )
    {
        $this->host = empty($host) ? $_ENV['DB_HOST'] : $host;
        $this->db = empty($db) ? $_ENV['DB_NAME'] : $db ;
        $this->user = empty($user) ? $_ENV['DB_USER'] : $user;
        $this->password = empty($password) ? $_ENV['DB_PASS'] : $password;
    }

    public function getConnection():PDO
    {
        $dsn = "mysql:host=$this->host;dbname=$this->db;charset=utf8";
        return new PDO($dsn, $this->user, $this->password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }
}