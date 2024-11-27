<?php

namespace App\Infrastructure\Slim\Middleware;

use App\Application\Users\GetActiveSessionUserQueryHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Routing\RouteContext;

class SessionValid
{
    public function __construct(private GetActiveSessionUserQueryHandler $query)
    {

    }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $context = RouteContext::fromRequest($request);

        $route = $context->getRoute();

        $id = $route->getArgument('sessionId') ?? null;
        $result = $this->query->handle($id);
        if (is_null($result['data'])) {
            throw new HttpUnauthorizedException($request, 'Access denied');
        }

        return $handler->handle($request);
    }
}