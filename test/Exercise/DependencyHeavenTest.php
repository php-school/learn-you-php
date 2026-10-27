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
    private Generator $faker;

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
        $this->runExercise('no-composer/solution.php', self::DIRECTORY_SOLUTION);

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
        $this->runExercise('no-code/solution.php', self::DIRECTORY_SOLUTION);

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'No code was found');
    }

    public function testWithWrongEndpoint(): void
    {
        $this->runExercise('wrong-endpoint/solution.php', self::DIRECTORY_SOLUTION);

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
        $this->runExercise('correct-solution/solution.php', self::DIRECTORY_SOLUTION);

        $this->assertVerifyWasSuccessful();
    }
}
