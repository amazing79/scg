<?php

namespace App\Domain\Users;

use App\Domain\Users\ValueObjects\Email;

class User
{
    public function __construct(
        private int $idUser,
        private string $name,
        private ?Email $email,
        private string $password,
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

    public static function fromArray(array $data): User
    {
        $idUser = $data['id'] ?? 0;
        $name = $data['userName'] ?? '';
        $email = isset($data['email']) ? new Email($data['email']) : null;
        $password = $data['password'] ?? '';
        return new self($idUser, $name, $email, $password);
    }

    private function assertValidUserName(string $name)
    {
        if(empty(trim($name))){
            throw new \Exception("Name can't be empty");
        }
    }

    private function assertValidPassword(string $password)
    {
        if(empty(trim($password))){
            throw new \Exception("Password can't be empty");
        }
    }

    private function assertValidEmail(?Email $email)
    {
        if(empty($email)){
            throw new \Exception("Email can't be empty");
        }
    }
}