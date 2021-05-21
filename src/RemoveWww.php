<?php
namespace Plinct\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

class RemoveWww implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $uri = $request->getUri();
        if (strpos($uri->getHost(),"www")) {
            $newHost = str_replace("www.", "", $uri->getHost());
            $newLocation = $uri->getScheme() . '://' . $newHost . $uri->getPath();
            $response = new Response();
            return $response->withHeader('Location', (string) $newLocation)->withStatus(301);
        }
        return $handler->handle($request);
    }
}