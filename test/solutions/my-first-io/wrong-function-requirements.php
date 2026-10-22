<?php

echo count(file($argv[1], FILE_IGNORE_NEW_LINES)) - 1;