<?php

$filePaths = $argv;
array_shift($filePaths);

foreach ($filePaths as $filePath) {
    try {
        $file = new SplFileObject($filePath);
    } catch (RuntimeException $e) {
        echo sprintf("Unable to open path file at path '%s'\n", $filePath);
    }
}
