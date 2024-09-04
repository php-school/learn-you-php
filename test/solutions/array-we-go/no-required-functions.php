<?php

$files = [];
for ($i = 1; $i < count($argv); $i++) {
    if (file_exists($argv[$i])) {
        echo basename($argv[$i]) . "\n";
    }
}