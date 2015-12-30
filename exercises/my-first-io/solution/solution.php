<?php

$contents = file_get_contents($argv[1]);
$lines = explode("\n", $contents);
echo count($lines);