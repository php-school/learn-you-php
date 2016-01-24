<?php


namespace PhpSchool\LearnYouPhpTest\Exercise;

use Faker\Factory;
use Faker\Generator;
use Hoa\Core\Exception\Exception;
use Hoa\Socket\Client;
use PhpSchool\LearnYouPhp\Exercise\ArrayWeGo;
use PhpSchool\LearnYouPhp\Exercise\TimeServer;
use PhpSchool\LearnYouPhp\TcpSocketFactory;
use PhpSchool\PhpWorkshop\Check\CheckRepository;
use PhpSchool\PhpWorkshop\Event\EventDispatcher;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\ExerciseDispatcher;
use PhpSchool\PhpWorkshop\Factory\RunnerFactory;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\Result\StdOutFailure;
use PhpSchool\PhpWorkshop\Result\Success;
use PhpSchool\PhpWorkshop\ResultAggregator;
use PhpSchool\PhpWorkshop\Solution\SolutionInterface;
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
     * @var ExerciseDispatcher
     */
    private $exerciseDispatcher;

    public function setUp()
    {
        $results = new ResultAggregator;
        $this->exerciseDispatcher = new ExerciseDispatcher(
            new RunnerFactory,
            $results,
            new EventDispatcher($results),
            new CheckRepository([])
        );
        $this->exercise = new TimeServer(new TcpSocketFactory);
    }

    public function testGetters()
    {
        $this->assertEquals('Time Server', $this->exercise->getName());
        $this->assertEquals('Build a Time Server!', $this->exercise->getDescription());
        $this->assertEquals(ExerciseType::CLI, $this->exercise->getType());

        $this->assertInstanceOf(SolutionInterface::class, $this->exercise->getSolution());
        $this->assertFileExists(realpath($this->exercise->getProblem()));
        $this->assertNull($this->exercise->tearDown());
    }

    public function testFailureIsReturnedIfCannotConnect()
    {
        $results = $this->exerciseDispatcher->verify($this->exercise, 'failure.php');
        $this->assertCount(2, $results);

        $failure = iterator_to_array($results)[0];
        $this->assertInstanceOf(Failure::class, $failure);

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $reason  = '/^Client returns an error \(number \d+\): No connection could be made because';
            $reason .= ' the target machine actively refused it\.\r\n';
            $reason .= ' while trying to join tcp:\/\/127\.0\.0\.1:\d+\.$/';
        } else {
            $reason  = '/^Client returns an error \(number \d+\): Connection refused';
            $reason .= ' while trying to join tcp:\/\/127\.0\.0\.1:\d+\.$/';
        }

        $this->assertRegExp($reason, $failure->getReason());
        $this->assertEquals('Time Server', $failure->getCheckName());
    }

    public function testFailureIsReturnedIfOutputWasNotCorrect()
    {
        $results = $this->exerciseDispatcher->verify(
            $this->exercise,
            __DIR__ . '/../res/time-server/solution-wrong.php'
        );

        $this->assertCount(2, $results);
        $failure = iterator_to_array($results)[0];

        $this->assertInstanceOf(StdOutFailure::class, $failure);
        $this->assertNotEquals($failure->getExpectedOutput(), $failure->getActualOutput());
        $this->assertEquals('Time Server', $failure->getCheckName());
    }

    public function testSuccessIsReturnedIfOutputIsCorrect()
    {
        $results = $this->exerciseDispatcher->verify(
            $this->exercise,
            __DIR__ . '/../res/time-server/solution.php'
        );

        $this->assertCount(2, $results);
        $success = iterator_to_array($results)[0];
        $this->assertInstanceOf(Success::class, $success);
    }
}
