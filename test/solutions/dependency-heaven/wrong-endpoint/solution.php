<?php

// Require composer autoloader
require __DIR__ . '/vendor/autoload.php';

use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use League\Route\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function Symfony\Component\String\s;

$request = \Laminas\Diactoros\ServerRequestFactory::fromGlobals();

$router = new Router();

$router->post('/wat', function (ServerRequestInterface $request): ResponseInterface {
    $str = $request->getQueryParams()['data'] ?? '';

    return new \Laminas\Diactoros\Response\JsonResponse([
        'result' => s($str)->reverse()->toString()
    ]);
});

$router->post('/snake', function (ServerRequestInterface $request): ResponseInterface {
    $str = $request->getQueryParams()['data'] ?? '';

    return new \Laminas\Diactoros\Response\JsonResponse([
        'result' => s($str)->snake()->toString()
    ]);
});


$router->post('/titleize', function (ServerRequestInterface $request): ResponseInterface {
    $str = $request->getQueryParams()['data'] ?? '';

    return new \Laminas\Diactoros\Response\JsonResponse([
        'result' => s($str)->title(true)->toString()
    ]);
});

$response = $router->dispatch($request);

(new SapiEmitter())->emit($response);
