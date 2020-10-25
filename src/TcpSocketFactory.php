<?php

namespace PhpSchool\LearnYouPhp;

use Hoa\Socket\Client;

class TcpSocketFactory
{
    public function createClient(string $ip, int $port): Client
    {
        return new Client(sprintf('tcp://%s:%d', $ip, $port));
    }
}
