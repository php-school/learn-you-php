<?php

namespace PhpSchool\LearnYouPhpTest;

use Hoa\Socket\Client;
use PhpSchool\LearnYouPhp\TcpSocketFactory;
use PHPUnit\Framework\TestCase;

/**
 * Class TcpSocketFactoryTest
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class TcpSocketFactoryTest extends TestCase
{
    public function testCreateClient(): void
    {
        $factory = new TcpSocketFactory();
        $client = $factory->createClient('127.0.0.1', 65000);

        $this->assertInstanceOf(Client::class, $client);
        $this->assertSame('tcp://127.0.0.1:65000', $client->getSocket()->__toString());
    }
}
