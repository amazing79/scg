<?php

namespace App\Application\Users;

use App\Domain\Common\Conts\HttpStatusCode;
use App\Domain\Common\Conts\HttpStatusMessages;
use App\Domain\Users\User;
use App\Domain\Users\UsersRepository;

class CreateUserCommandHandler
{
    public function __construct(
        private UsersRepository $repository,
    )
    {
    }

    public function handle(array $values): array
    {
        $response = [];
        $response['code'] = HttpStatusCode::INTERNAL_SERVER_ERROR;
        $response['message'] = HttpStatusMessages::getMessage(HttpStatusCode::INTERNAL_SERVER_ERROR);
        try {
            $password = $this->addPeeperToPassword($values);
            $values['password'] = password_hash($password, PASSWORD_BCRYPT);
            $user = User::fromArray($values);
            $id = $this->repository->addUser($user);

            $response['code'] = HttpStatusCode::OK;
            $response['message'] = "Usuario creado con exito con el id ${id}";
        } catch (\Exception $e) {
            $response['message'] = "Code error: {$e->getCode()} - descripcion: {$e->getMessage()}";
        }
        return $response;
    }

    private function addPeeperToPassword($credentials): string
    {
        return hash_hmac("sha256", $credentials['password'], $credentials['pepper']);
    }

}