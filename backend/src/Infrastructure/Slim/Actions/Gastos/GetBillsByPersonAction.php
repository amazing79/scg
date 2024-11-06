<?php

namespace App\Infrastructure\Slim\Actions\Gastos;

use App\Application\Gastos\GetGastosByPersonaQueryHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetBillsByPersonAction
{

    public function __construct(Private GetGastosByPersonaQueryHandler $query)
    {
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $persona = (int) $args['idPersona'];
        $filter = $request->getParsedBody();
        $result = $this->query->handle($persona, $filter);
        $dataAsJson = json_encode($result, JSON_PRETTY_PRINT);
        $response->getBody()->write($dataAsJson);
        return $response->withStatus($result['code']);
    }
}