<?php

foreach (new DirectoryIterator($argv[1]) as $file) {
    echo $file->getFilename() . "\n";
}
