<?php


namespace PhpSchool\LearnYouPhpTest\Exercise;

use Faker\Factory;
use Faker\Generator;
use Hoa\Core\Exception\Exception;
use Hoa\Socket\Client;
use PhpSchool\LearnYouPhp\Exercise\ArrayWeGo;
use PhpSchool\LearnYouPhp\Exercise\TimeServer;
use PhpSchool\LearnYouPhp\TcpSocketFactory;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\Result\StdOutFailure;
use PhpSchool\PhpWorkshop\Result\Success;
use PHPUnit_Framework_TestCase;
use PhpSchool\LearnYouPhp\Exercise\MyFirstIo;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class TimeServerTest
 * @package PhpSchool\LearnYouPhpTest\Exercise
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
class TimeServerTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var TimeServer
     */
    private $exercise;

    /**
     * @var TcpSocketFactory
     */
    private $socketFactory;

    public function setUp()
    {
        $this->socketFactory = $this->getMock(TcpSocketFactory::class);
        $this->exercise = new TimeServer($this->socketFactory);
    }

    public function testGetters()
    {
        $this->assertEquals('Time Server', $this->exercise->getName());
        $this->assertEquals('Build a Time Server!', $this->exercise->getDescription());

        $this->assertFileExists(realpath($this->exercise->getSolution()));
        $this->assertFileExists(realpath($this->exercise->getProblem()));
        $this->assertNull($this->exercise->tearDown());
    }

    public function testFailureIsReturnedIfCannotConnect()
    {
        $this->socketFactory
            ->expects($this->once())
            ->method('createClient')
            ->with('127.0.0.1', $this->logicalAnd(
                $this->greaterThan(1024),
                $this->lessThan(655356)
            ))
            ->will($this->returnValue(new Client('tcp://127.0.0.1:655355')));
        
        $failure = $this->exercise->check('program.php');
        
        $this->assertInstanceOf(Failure::class, $failure);


        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $reason  = '/^Client returns an error \(number 10061\): No connection could be made because';
            $reason .= ' the target machine actively refused it\.\r\n';
            $reason .= ' while trying to join tcp:\/\/127\.0\.0\.1:655355\.$/';
        } else {
            $reason  = '/^Client returns an error \(number 61\): Connection refused';
            $reason .= ' while trying to join tcp:\/\/127\.0\.0\.1:655355\.$/';
        }

        $this->assertRegExp($reason, $failure->getReason());
        $this->assertEquals('Time Server', $failure->getCheckName());
    }
    
    public function testFailureIsReturnedIfOutputWasNotCorrect()
    {
        $e = new TimeServer(new TcpSocketFactory);
        $failure = $e->check(__DIR__ . '/../res/time-server/solution-wrong.php');
        $this->assertInstanceOf(StdOutFailure::class, $failure);
        $this->assertNotEquals($failure->getExpectedOutput(), $failure->getActualOutput());
        $this->assertEquals('Time Server', $failure->getCheckName());
    }

    public function testSuccessIsReturnedIfOutputIsCorrect()
    {
        $e = new TimeServer(new TcpSocketFactory);
        $success = $e->check(__DIR__ . '/../res/time-server/solution.php');
        $this->assertInstanceOf(Success::class, $success);
    }

    public function testProcessIsStoppedIfStillRunning()
    {
        $e = new TimeServer(new TcpSocketFactory);
        $failure = $e->check(__DIR__ . '/../res/time-server/solution-keep-running.php');
        $this->assertInstanceOf(Failure::class, $failure);
        $this->assertEquals('', $failure->getReason());
        $this->assertEquals('Time Server', $failure->getCheckName());
    }

    public function testFailureIsReturnedIfProcessWasNotSuccessful()
    {
        $e = new TimeServer(new TcpSocketFactory);
        $failure = $e->check(__DIR__ . '/../res/time-server/solution-bad-exit.php');
        $this->assertInstanceOf(Failure::class, $failure);
        $this->assertEquals('', $failure->getReason());
        $this->assertEquals('Time Server', $failure->getCheckName());
    }
}
