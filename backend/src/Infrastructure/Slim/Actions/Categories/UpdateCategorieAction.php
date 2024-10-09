<?php

namespace App\Infrastructure\Slim\Actions\Categories;

use App\Application\Categorias\UpdateCategoriaCommandHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UpdateCategorieAction
{
    private UpdateCategoriaCommandHandler $command;

    public function __construct(UpdateCategoriaCommandHandler $command)
    {
        $this->command = $command;
    }

    public function __invoke(Request $request, Response $response, $args): Response
    {
        $values = $request->getParsedBody();
        $values['id'] = $args['id'];
        $result = $this->command->handle($values);
        $dataAsJson = json_encode($result, JSON_PRETTY_PRINT);

        $response->getBody()->write($dataAsJson);
        return $response->withStatus($result['code']);
    }
}