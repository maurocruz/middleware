<?php
namespace Plinct\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class RedirectHttps implements MiddlewareInterface {

    public function process(Request $request, RequestHandler $handler): ResponseInterface {
        $response = $handler->handle($request);
        if (substr($_SERVER['HTTP_HOST'], 0, 4) === 'www.' || $_SERVER['SERVER_PORT'] != 443 && (self::checkForwaredPort() === false && self::checkForwaredProto() === false)) {
            $host = substr($_SERVER['HTTP_HOST'], 0, 4) === 'www.' ? substr($_SERVER['HTTP_HOST'], 4) : $_SERVER['HTTP_HOST'];
            return $response->withHeader('Location', 'https://' . $host . $_SERVER['REQUEST_URI'])->withStatus(302);
        } else {
            return $response;
        }
    }
    
    private function checkForwaredPort(): bool {
        if (array_key_exists('HTTP_X_FORWARDED_PORT', $_SERVER) && $_SERVER['HTTP_X_FORWARDED_PORT'] == 445){
            return true;
        } else {
            return false;
        }
    }
    
    private function checkForwaredProto(): bool {
        if (array_key_exists('HTTP_X_FORWARDED_PROTO', $_SERVER) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == "https"){
            return true;
        } else {
            return false;
        }
    }
}
