<?php

namespace App\Infrastructure\Slim\Actions\Gastos;

use App\Application\Gastos\GetGastoByIdQueryHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ShowBillAction
{
    public function __construct(private GetGastoByIdQueryHandler $query)
    {
    }

    public function __invoke(Request $request, Response $response, $args): Response
    {
        $id = $args['id'];
        $result = $this->query->handle((int) $id);
        $dataAsJson = json_encode($result, JSON_PRETTY_PRINT);

        $response->getBody()->write($dataAsJson);
        return $response->withStatus($result['code']);
    }
}