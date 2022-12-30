<?php

require __DIR__ . '/vendor/autoload.php';

use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use League\Route\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function Symfony\Component\String\s;

$request = ServerRequestFactory::fromGlobals();

$router = new Router();

$router->post('/wat', function (ServerRequestInterface $request): ResponseInterface {
    $str = $request->getParsedBody()['data'] ?? '';

    return new JsonResponse([
        'result' => s($str)->reverse()->toString()
    ]);
});

$router->post('/snake', function (ServerRequestInterface $request): ResponseInterface {
    $str = $request->getParsedBody()['data'] ?? '';

    return new JsonResponse([
        'result' => s($str)->snake()->toString()
    ]);
});


$router->post('/titleize', function (ServerRequestInterface $request): ResponseInterface {
    $str = $request->getParsedBody()['data'] ?? '';

    return new JsonResponse([
        'result' => s($str)->title(true)->toString()
    ]);
});

$response = $router->dispatch($request);

(new SapiEmitter())->emit($response);
