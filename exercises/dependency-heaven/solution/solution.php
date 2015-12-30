<?php

// Require composer autoloader
require __DIR__ . '/vendor/autoload.php';

use Klein\Klein;
use Klein\Request;
use Klein\Response;
use Stringy\Stringy as S;

$klein = new Klein();

$klein->respond('POST', '/reverse', function (Request $req, Response $res) {
    $res->json(['result' => (new S($req->param('data', '')))->reverse()->__toString()]);
});

$klein->respond('POST', '/swapcase', function (Request $req, Response $res) {
    $res->json(['result' => (new S($req->param('data', '')))->swapCase()->__toString()]);
});

$klein->respond('POST', '/titleize', function (Request $req, Response $res) {
    $res->json(['result' => (new S($req->param('data', '')))->titleize()->__toString()]);
});

$klein->dispatch();