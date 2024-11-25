<?php

namespace App\Infrastructure\Users;

use App\Domain\Users\User;
use App\Domain\Users\UsersRepository;
use App\Infrastructure\Common\Database;
use PDO;

class PdoUsersRepository implements UsersRepository
{

    /**
     * @param Database $db
     */
    public function __construct(private Database $db)
    {
    }
    public function login($credentials): ?User
    {

        $sql = "
                SELECT idUser, userName, email, password 
                FROM users 
                WHERE email = :email or userName = :email
                ;
                ";

        $pdo = $this->db->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':email', $credentials['email']);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!empty($result)){
            return User::fromArray($result);
        }
        return null;
    }

}