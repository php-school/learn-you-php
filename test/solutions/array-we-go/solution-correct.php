<?php

$filePaths = $argv;
array_shift($filePaths);

$filePaths = array_filter($filePaths, 'file_exists');
$fileObjects = array_map(function ($filePath) {
    return new SplFileObject($filePath);
}, $filePaths);

foreach ($fileObjects as $fileObject) {
    echo sprintf("%s\n", $fileObject->getBasename());
}
