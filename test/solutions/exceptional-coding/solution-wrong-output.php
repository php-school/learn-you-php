<?php

$filePaths = $argv;
array_shift($filePaths);

foreach ($filePaths as $filePath) {
    echo basename($filePath);
}
