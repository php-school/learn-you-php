<?php


namespace PhpSchool\LearnYouPhpTest\Exercise;

use Colors\Color;
use PhpSchool\CliMenu\Terminal\TerminalInterface;
use PhpSchool\LearnYouPhp\Exercise\TimeServer;
use PhpSchool\LearnYouPhp\TcpSocketFactory;
use PhpSchool\PhpWorkshop\Check\CheckRepository;
use PhpSchool\PhpWorkshop\Event\EventDispatcher;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\ExerciseDispatcher;
use PhpSchool\PhpWorkshop\ExerciseRunner\CliRunner;
use PhpSchool\PhpWorkshop\ExerciseRunner\Factory\CliRunnerFactory;
use PhpSchool\PhpWorkshop\ExerciseRunner\RunnerManager;
use PhpSchool\PhpWorkshop\Factory\RunnerFactory;
use PhpSchool\PhpWorkshop\Input\Input;
use PhpSchool\PhpWorkshop\Output\StdOutput;
use PhpSchool\PhpWorkshop\Result\ComparisonFailure;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\Result\StdOutFailure;
use PhpSchool\PhpWorkshop\Result\Success;
use PhpSchool\PhpWorkshop\ResultAggregator;
use PhpSchool\PhpWorkshop\Solution\SolutionInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
class TimeServerTest extends TestCase
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
        $eventDispatcher = new EventDispatcher($results);

        $r = new \ReflectionClass(CliRunner::class);
        $rp = $r->getProperty('requiredChecks');
        $rp->setAccessible(true);
        $rp->setValue([]);

        $runnerManager = new RunnerManager;
        $runnerManager->addFactory(new CliRunnerFactory($eventDispatcher));
        $this->exerciseDispatcher = new ExerciseDispatcher(
            $runnerManager,
            $results,
            $eventDispatcher,
            new CheckRepository()
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
        $input = new Input('learnyouphp', ['program' => __DIR__ . '/../res/time-server/no-server.php']);
        $results = $this->exerciseDispatcher->verify($this->exercise, $input);
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
        $input = new Input('learnyouphp', ['program' => __DIR__ . '/../res/time-server/solution-wrong.php']);
        $results = $this->exerciseDispatcher->verify($this->exercise, $input);

        $this->assertCount(2, $results);
        $failure = iterator_to_array($results)[0];

        $this->assertInstanceOf(ComparisonFailure::class, $failure);
        $this->assertNotEquals($failure->getExpectedValue(), $failure->getActualValue());
        $this->assertEquals('Time Server', $failure->getCheckName());
    }

    public function testSuccessIsReturnedIfOutputIsCorrect()
    {
        $input = new Input('learnyouphp', ['program' => __DIR__ . '/../res/time-server/solution.php']);
        $results = $this->exerciseDispatcher->verify($this->exercise, $input);

        $this->assertCount(2, $results);
        $success = iterator_to_array($results)[0];
        $this->assertInstanceOf(Success::class, $success);
    }

    public function testRun()
    {
        $color = new Color;
        $color->setForceStyle(true);
        $output = new StdOutput($color, $terminal = $this->createMock(TerminalInterface::class));
        
        $outputRegEx  = "/\n";
        $outputRegEx .= '\[1m\[4mArguments\[0m\[0m';
        $outputRegEx .= "\n";
        $outputRegEx .= '127.0.0.1, \d+';
        $outputRegEx .= "\n\n";
        $outputRegEx .= '\[1m\[4mOutput\[0m\[0m';
        $outputRegEx .= "\n";
        $outputRegEx .= '\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}';
        $outputRegEx .= "\n/";
        $this->expectOutputRegex($outputRegEx);

        $input = new Input('learnyouphp', ['program' => __DIR__ . '/../res/time-server/solution.php']);
        $this->exerciseDispatcher->run($this->exercise, $input, $output);
    }
}
