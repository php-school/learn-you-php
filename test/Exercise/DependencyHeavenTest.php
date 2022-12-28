<?php

namespace PhpSchool\LearnYouPhpTest\Exercise;

use Faker\Factory;
use Faker\Generator;
use PhpSchool\LearnYouPhp\Exercise\DependencyHeaven;
use PhpSchool\PhpWorkshop\Application;
use PhpSchool\PhpWorkshop\Check\ComposerCheck;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\ExerciseDispatcher;
use PhpSchool\PhpWorkshop\Result\Cgi\ResultInterface;
use PhpSchool\PhpWorkshop\Result\ComposerFailure;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\TestUtils\WorkshopExerciseTest;
use Psr\Http\Message\RequestInterface;
use PhpSchool\PhpWorkshop\Result\Cgi\GenericFailure;

use function PhpSchool\PhpWorkshop\collect;

class DependencyHeavenTest extends WorkshopExerciseTest
{
    /**
     * @var Generator
     */
    private $faker;

    public function setUp(): void
    {
        $this->faker = Factory::create('Fr_fr');
        parent::setUp();
    }

    public function testDependencyHeavenExercise(): void
    {
        $e = new DependencyHeaven($this->faker);
        $this->assertEquals('Dependency Heaven', $e->getName());
        $this->assertEquals('An introduction to Composer dependency management', $e->getDescription());
        $this->assertEquals(ExerciseType::CGI, $e->getType());

        $this->assertFileExists(realpath($e->getSolution()->getEntryPoint()));
        $this->assertFileExists(realpath($e->getProblem()));
    }

    public function testGetRequiredPackages(): void
    {
        $this->assertSame(
            ['league/route', 'laminas/laminas-diactoros', 'laminas/laminas-httphandlerrunner', 'symfony/string'],
            (new DependencyHeaven($this->faker))->getRequiredPackages()
        );
    }

    public function testGetRequests(): void
    {
        $e = new DependencyHeaven($this->faker);

        $requests  = $e->getRequests();

        foreach ($requests as $request) {
            $this->assertInstanceOf(RequestInterface::class, $request);
            $this->assertSame('POST', $request->getMethod());
            $this->assertSame(['application/x-www-form-urlencoded'], $request->getHeader('Content-Type'));
            $this->assertNotEmpty($request->getBody());
        }
    }

    public function testGetRequestsReturnsMultipleRequestsForEachEndpoint(): void
    {
        $e = new DependencyHeaven($this->faker);

        $endPoints = array_map(function (RequestInterface $request) {
            return $request->getUri()->getPath();
        }, $e->getRequests());

        $counts = array_count_values($endPoints);
        foreach (['/reverse', '/snake', '/titleize'] as $endPoint) {
            $this->assertTrue(isset($counts[$endPoint]));
            $this->assertGreaterThan(1, $counts[$endPoint]);
        }
    }

    public function testConfigure(): void
    {
        $dispatcher = $this->getMockBuilder(ExerciseDispatcher::class)
            ->disableOriginalConstructor()
            ->getMock();

        $dispatcher
            ->expects($this->once())
            ->method('requireCheck')
            ->with(ComposerCheck::class);

        $e = new DependencyHeaven($this->faker);
        $e->configure($dispatcher);
    }

    public function getExerciseClass(): string
    {
        return DependencyHeaven::class;
    }

    public function getApplication(): Application
    {
        return require __DIR__ . '/../../app/bootstrap.php';
    }

    public function testWithNoComposerFile(): void
    {
        $this->runExercise('no-composer/solution.php');

        $this->assertVerifyWasNotSuccessful();
        $this->assertResultsHasFailureAndMatches(
            ComposerFailure::class,
            function (ComposerFailure $failure) {
                return $failure->getMissingComponent() === 'composer.json';
            }
        );
    }

    public function testWithNoCode(): void
    {
        $this->runExercise('no-code/solution.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'No code was found');
    }

    public function testWithWrongEndpoint(): void
    {
        $this->runExercise('wrong-endpoint/solution.php');

        $this->assertVerifyWasNotSuccessful();

        $result = $this->getOutputResult();

        $reverseRequests = collect($result->getResults())
            ->filter(function (ResultInterface $result) {
                return $result->getRequest()->getUri()->getPath() === '/reverse';
            });

        $this->assertGreaterThan(1, $reverseRequests->count());

        $fails = collect($result->getResults())
            ->filter(function ($result) {
                return $result instanceof GenericFailure;
            });

        $this->assertSame($reverseRequests->count(), $fails->count());

        $fails->each(function (GenericFailure $failure) {
            $this->assertStringContainsString(
                'Uncaught League\Route\Http\Exception\NotFoundException',
                $failure->getReason()
            );
        });
    }

    public function testWithCorrectSolution(): void
    {
        $this->runExercise('correct-solution/solution.php');

        $this->assertVerifyWasSuccessful();
    }
}
