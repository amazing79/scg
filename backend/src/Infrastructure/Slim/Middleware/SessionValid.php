<?php

namespace App\Infrastructure\Slim\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Routing\RouteContext;

class SessionValid
{
    const SESSION_ID = 'ae0a0209-aaca-11ef-a6fb-54e1ad9390b4';

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $context = RouteContext::fromRequest($request);

        $route = $context->getRoute();

        $id = $route->getArgument('sessionId') ?? null;

        if ($id !== self::SESSION_ID) {
            throw new HttpUnauthorizedException($request, 'Access denied');
        }

        return $handler->handle($request);
    }
}