<?php

namespace App\Application\Users;

use App\Domain\Common\Conts\HttpStatusCode;
use App\Domain\Common\Conts\HttpStatusMessages;
use App\Domain\Users\UsersRepository;

class GetActiveSessionUserQueryHandler
{
    public function __construct(private UsersRepository $repository)
    {
    }

    public function handle($sessionId): array
    {
        $response = [];
        $response['code'] = HttpStatusCode::INTERNAL_SERVER_ERROR;
        $response['message'] = HttpStatusMessages::getMessage(HttpStatusCode::INTERNAL_SERVER_ERROR);
        try {
            $user = $this->repository->getUserbySessionId($sessionId);
            $response['code'] = HttpStatusCode::OK;
            $response['data'] = $user;
            $response['message'] = 'Usuario de la sesion obtenido correctamente';
        } catch (\Exception $e) {
            $response['message'] = "Code error: {$e->getCode()} - descripcion: {$e->getMessage()}";
        } finally {
            return $response;
        }
    }
}