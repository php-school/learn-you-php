<?php

$contents = file_get_contents($argv[1]);
echo substr_count($contents, "\n");