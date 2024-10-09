<?php

namespace App\Infrastructure\Slim\Actions\Categories;

use App\Application\Categorias\GetCategoriasQueryHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetCategoriesAction
{
    private GetCategoriasQueryHandler $query;

    public function __construct(GetCategoriasQueryHandler $query)
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