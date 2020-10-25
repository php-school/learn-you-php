<?php

namespace PhpSchool\LearnYouPhp;

use Hoa\Socket\Client;

class TcpSocketFactory
{
    /**
     * @param string $ip
     * @param int $port
     * @return Client
     */
    public function createClient(string $ip, int $port): Client
    {
        return new Client(sprintf('tcp://%s:%d', $ip, $port));
    }
}
