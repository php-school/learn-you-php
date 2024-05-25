<?php

$filePaths = $argv;
array_shift($filePaths);

foreach ($filePaths as $filePath) {
    if (file_exists($filePath)) {
        echo sprintf("%s\n", basename($filePath));
    } else {
        echo sprintf("Unable to open file at path '%s'\n", $filePath);
    }
}
