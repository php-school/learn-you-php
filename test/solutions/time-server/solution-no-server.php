<?php

$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_bind($sock, $argv[1], $argv[2]);