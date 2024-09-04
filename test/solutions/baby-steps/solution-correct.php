<?php

$count = 0;
for ($i = 1; $i < count($argv); $i++) {
    $count += $argv[$i];
}

echo $count;