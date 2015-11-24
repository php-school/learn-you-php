<?php

date_default_timezone_set('Europe/London');
$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_bind($sock, $argv[1], $argv[2]);

socket_listen($sock);

$clientSock = socket_accept($sock);
$date = (new \DateTime)->format('Y-m-d H:i:s') . "\n";
socket_write($clientSock, $date, strlen($date));
socket_close($clientSock);
socket_close($sock);
