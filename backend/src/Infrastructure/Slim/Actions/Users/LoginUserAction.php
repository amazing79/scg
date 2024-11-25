<?php

namespace App\Infrastructure\Slim\Actions\Users;

use App\Application\Users\LoginUserCommandHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class LoginUserAction
{
    /**
     * @param LoginUserCommandHandler $command
     */
    public function __construct(private LoginUserCommandHandler $command)
    {
    }

    public function __invoke(Request $request, Response $response, $args): Response
    {
        $values = $request->getParsedBody();
        $values['pepper'] =  $_ENV['SECRET'];
        $result = $this->command->handle($values);
        $dataAsJson = json_encode($result, JSON_PRETTY_PRINT);
        $response->getBody()->write($dataAsJson);
        return $response->withStatus($result['code']);
    }
}