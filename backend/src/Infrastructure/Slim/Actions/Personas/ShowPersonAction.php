<?php

namespace App\Infrastructure\Slim\Actions\Personas;

use App\Application\Personas\GetPersonaByIdQueryHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ShowPersonAction
{
    private GetPersonaByIdQueryHandler $query;

    /**
     * @param GetPersonaByIdQueryHandler $query
     */
    public function __construct(GetPersonaByIdQueryHandler $query)
    {
        $this->query = $query;
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