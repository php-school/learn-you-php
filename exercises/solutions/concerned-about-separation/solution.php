<?php

date_default_timezone_set('Europe/London');
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/DirectoryFilter.php';
array_map(function ($fileName) {
    echo $fileName . '
';
}, (new DirectoryFilter())->getFiles($argv[1], $argv[2]));