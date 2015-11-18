<?php

namespace PhpSchool\LearnYouPhpTest;

use Hoa\Socket\Client;
use PhpSchool\LearnYouPhp\TcpSocketFactory;
use PHPUnit_Framework_TestCase;

/**
 * Class TcpSocketFactoryTest
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class TcpSocketFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testCreateClient()
    {
        $factory = new TcpSocketFactory;
        $client = $factory->createClient('127.0.0.1', 65000);
        
        $this->assertInstanceOf(Client::class, $client);
        $this->assertSame('tcp://127.0.0.1:65000', $client->getSocket()->__toString());
    }
}
