<?php

namespace App\Infrastructure\Slim\Actions\Personas;

use App\Application\Personas\CreatePersonaCommandHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CreatePersonAction
{
    private CreatePersonaCommandHandler $command;

    /**
     * @param CreatePersonaCommandHandler $command
     */
    public function __construct(CreatePersonaCommandHandler $command)
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