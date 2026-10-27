<?php

require_once __DIR__ . '/DirectoryFilter.php';

array_map(function ($fileName) {
    echo $fileName . "\n";
}, (new DirectoryFilter)->getFiles($argv[1], $argv[2]));