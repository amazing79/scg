<?php

namespace App\Infrastructure\Slim\Actions\Gastos;

use App\Application\Gastos\CreateGastoCommandHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CreateBillAction
{
    public function __construct(private CreateGastoCommandHandler $command)
    {
    }

    public function __invoke(Request $request, Response $response, $args): Response
    {
        $values = $request->getParsedBody();
        $result = $this->command->handle($values);
        $dataAsJson = json_encode($result, JSON_PRETTY_PRINT);

        $response->getBody()->write($dataAsJson);
        return $response->withStatus($result['code']);
    }
}