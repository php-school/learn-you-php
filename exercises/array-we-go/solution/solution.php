<?php

date_default_timezone_set('Europe/London');
error_reporting(E_ALL);
ini_set('display_errors', 1);
$filePaths = $argv;
array_shift($filePaths);
$filePaths = array_filter($filePaths, 'file_exists');
$fileObjects = array_map(function ($filePath) {
    return new SplFileObject($filePath);
}, $filePaths);
foreach ($fileObjects as $fileObject) {
    echo sprintf('%s
', $fileObject->getBasename());
}