<?php

$filePaths = $argv;
array_shift($filePaths);

foreach ($filePaths as $filePath) {
    try {
        $file = new SplFileObject($filePath);
        echo sprintf("%s\n", $file->getBasename());
    } catch (RuntimeException $e) {
        echo sprintf("Unable to open file at path '%s'\n", $filePath);
    }
}
