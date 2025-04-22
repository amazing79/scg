<?php

namespace App\Domain\Users;

use App\Domain\Users\ValueObjects\Email;

class User
{
    /**
     * When creating a new user, if the email is not provided or a password is not provided, an exception is thrown.
     *
     * @throws \Exception
     */
    public function __construct(
        private int $idUser,
        private string $name,
        private ?Email $email,
        private string $password,
        private string $session_expiration
    )
    {
        $this->assertValidUserName($this->name);
        $this->assertValidEmail($this->email);
        $this->assertValidPassword($this->password);
    }

    public function getIdUser(): int
    {
        return $this->idUser;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email->getMail();
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getSessionExpiration(): string
    {
        return $this->session_expiration;
    }



    public static function fromArray(array $data): User
    {
        $idUser = $data['idUser'] ?? 0;
        $name = $data['userName'] ?? '';
        $email = isset($data['email']) ? new Email($data['email']) : null;
        $password = $data['password'] ?? '';
        $sessionExpiration = $data['expiration'] ?? '';
        return new self($idUser, $name, $email, $password, $sessionExpiration);
    }

    private function assertValidUserName(string $name): void
    {
        if(empty(trim($name))){
            throw new \Exception("Name can't be empty");
        }
    }

    private function assertValidPassword(string $password): void
    {
        if(empty(trim($password))){
            throw new \Exception("Password can't be empty");
        }
    }

    private function assertValidEmail(?Email $email): void
    {
        if(empty($email)){
            throw new \Exception("Email can't be empty");
        }
    }
}