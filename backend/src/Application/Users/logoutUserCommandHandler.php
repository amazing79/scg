<?php

namespace App\Application\Users;

use App\Domain\Common\Conts\HttpStatusCode;
use App\Domain\Common\Conts\HttpStatusMessages;
use App\Domain\Users\UsersRepository;

class logoutUserCommandHandler
{
    public function __construct(
        private UsersRepository $repository,
    )
    {
    }

    public function handle($sessionID): array
    {
        $response = [];
        $response['code'] = HttpStatusCode::INTERNAL_SERVER_ERROR;
        $response['message'] = HttpStatusMessages::getMessage(HttpStatusCode::INTERNAL_SERVER_ERROR);
        try {
            $this->repository->deleteSession($sessionID);
            $response['code'] = HttpStatusCode::OK;
            $response['message'] = HttpStatusMessages::getMessage(HttpStatusCode::OK);
        } catch (\Exception $e) {
            $response['message'] = "Code error: {$e->getCode()} - descripcion: {$e->getMessage()}";
        }
        return $response;
    }
}