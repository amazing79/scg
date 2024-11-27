<?php

namespace App\Infrastructure\Slim\Actions\Users;

use App\Application\Users\logoutUserCommandHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class LogoutUserAction
{
    public function __construct(private logoutUserCommandHandler $command)
    {
    }

    public function __invoke(Request $request, Response $response, $args): Response
    {
        $sessionId = $request->getAttribute("sessionId");
        $result = $this->command->handle($sessionId);
        $dataAsJson = json_encode($result, JSON_PRETTY_PRINT);
        $response->getBody()->write($dataAsJson);
        return $response->withStatus($result['code']);
    }

}