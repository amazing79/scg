<?php

namespace App\Infrastructure\Slim\Actions\Users;

use App\Application\Users\ResetUserPasswordCommandHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ResetUserPasswordAction
{
    /**
     * @param ResetUserPasswordCommandHandler $command
     */
    public function __construct(private ResetUserPasswordCommandHandler $command)
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