<?php

namespace App\Infrastructure\Slim\Actions\Categories;

use App\Application\Categorias\CreateCategoriaCommandHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CreateCategoryAction
{
    private CreateCategoriaCommandHandler $command;

    public function __construct(CreateCategoriaCommandHandler $command)
    {
        $this->command = $command;
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