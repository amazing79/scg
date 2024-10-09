<?php

namespace App\Infrastructure\Slim\Actions\Categories;

use App\Application\Categorias\GetCategoriaById;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ShowCategoryAction
{
    private GetCategoriaById $query;

    public function __construct(GetCategoriaById $query)
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