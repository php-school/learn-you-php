<?php

require_once __DIR__ . '/DirectoryFilter.php';

$filter = new DirectoryFilter;
$files = $filter->getFiles($argv[1], $argv[2]);

echo implode("\n", $files) . "\n";