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
    public function getUserByCredentials($credentials): ?User
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

    public function addUser(User $user): int
    {
        $sql = "
                INSERT INTO users (userName, email, password) 
                values (:userName, :email, :password) 
                ;
                ";

        $pdo = $this->db->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':userName', $user->getName());
        $stmt->bindValue(':email', $user->getEmail());
        $stmt->bindValue(':password', $user->getPassword());

        $stmt->execute();

        return $pdo->lastInsertId();
    }

    public function createUserSession(User $user): string
    {
        $uuid = $this->generateUuid();
        $expiration = date("Y-m-d H:i:s", strtotime("+30 minutes"));
        $sql = "
                INSERT INTO users_sessions (uuid, expiration, idUser, auth_token) 
                values (:uuid, :expiration, :idUser, :auth_token) 
                ;
                ";

        $pdo = $this->db->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':uuid', $uuid);
        $stmt->bindValue(':expiration', $expiration);
        $stmt->bindValue(':idUser', $user->getIdUser(), PDO::PARAM_INT);
        $stmt->bindValue(':auth_token', 'auth_token');

        $stmt->execute();

        return $uuid;
    }

    public function getUserBySessionId(string $sessionId): ?User
    {
        $sql = "
                SELECT us.idSession, us.uuid, us.expiration, u.idUser, us.auth_token,
                       u.userName, u.email, u.password
                FROM users_sessions us
                inner join users u on u.idUser = us.idUser
                WHERE 
                    us.uuid = :sessionId and us.expiration >= now()
                ;
                ";
        $pdo = $this->db->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':sessionId', $sessionId);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!empty($result)){
            return User::fromArray($result);
        }
        return null;
    }

    private function generateUuid():string
    {
        $sql = " SELECT UUID() as sessionId;";
        $pdo = $this->db->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['sessionId'];
    }

    public function deleteUserSession(string $sessionId): bool
    {
        $sql = 'DELETE FROM users_sessions WHERE uuid = :uuid';

        $pdo = $this->db->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':uuid', $sessionId);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function getActiveUserSession(int $idUser): string
    {
        $sql = "
                SELECT us.idSession, us.uuid, us.expiration, us.idUser, us.auth_token
                FROM users_sessions us
                WHERE 
                    us.idUser = :idUser
                ;
                ";
        $pdo = $this->db->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':idUser', $idUser,PDO::PARAM_INT );
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!empty($result)){
            return $result['uuid'];
        }
        return '';
    }
}