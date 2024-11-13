<?php

namespace App\Infrastructure\Slim\Actions\Gastos;

use App\Application\Gastos\GetGastosByCategoriaPersonaPeriodoQueryHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetBillsByCategoryPersonAction
{
    public function __construct(private GetGastosByCategoriaPersonaPeriodoQueryHandler $command)
    {
    }

    public function __invoke(Request $request, Response $response, $args): Response
    {
        $result = $this->command->handle();
        $dataAsJson = json_encode($result, JSON_PRETTY_PRINT);

        $response->getBody()->write($dataAsJson);
        return $response->withStatus($result['code']);
    }
}