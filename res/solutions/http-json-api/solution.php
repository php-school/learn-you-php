<?php

header('Content-Type: application/json');

$urlParts = parse_url($_SERVER['REQUEST_URI']);

if ($urlParts['path'] === '/api/parsetime') {

    if (isset($_GET['iso'])) {
        $date = new \DateTime($_GET['iso']);
        echo json_encode([
            "hour" => $date->format('H'),
            "minute" => $date->format('i'),
            "second" => $date->format('s')
        ]);
        exit;
    }
}

if ($urlParts['path'] === '/api/unixtime') {
    if (isset($_GET['iso'])) {
        $date = new \DateTime($_GET['iso']);
        echo json_encode(["unixtime" => $date->format('u')]);
        exit;
    }
}
