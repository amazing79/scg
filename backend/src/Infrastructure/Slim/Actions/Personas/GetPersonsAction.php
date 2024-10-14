<?php

namespace App\Infrastructure\Slim\Actions\Personas;

use App\Application\Personas\GetPersonasQueryHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetPersonsAction
{
    private GetPersonasQueryHandler $query;

    /**
     * @param GetPersonasQueryHandler $query
     */
    public function __construct(GetPersonasQueryHandler $query)
    {
        $this->query = $query;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $result = $this->query->handle();
        $dataAsJson = json_encode($result, JSON_PRETTY_PRINT);
        $response->getBody()->write($dataAsJson);
        return $response->withStatus($result['code']);
    }
}