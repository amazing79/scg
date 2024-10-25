<?php

namespace App\Infrastructure\Slim\Actions\Gastos;

use App\Application\Gastos\GetGastosQueryHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetBillsAction
{
    public function __construct(private GetGastosQueryHandler $query)
    {
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $result = $this->query->handle();
        $dataAsJson = json_encode($result, JSON_PRETTY_PRINT);
        $response->getBody()->write($dataAsJson);
        return $response->withStatus($result['code']);
    }
}