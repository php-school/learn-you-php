<?php

namespace PhpSchool\LearnYouPhpTest\Exercise;

use PhpSchool\LearnYouPhp\Exercise\TimeServer;
use PhpSchool\PhpWorkshop\Application;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\Input\Input;
use PhpSchool\PhpWorkshop\Result\ComparisonFailure;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\TestUtils\WorkshopExerciseTest;

class TimeServerTest extends WorkshopExerciseTest
{
    public function getApplication(): Application
    {
        return require __DIR__ . '/../../app/bootstrap.php';
    }

    public function getExerciseClass(): string
    {
        return TimeServer::class;
    }

    public function testExerciseMeta(): void
    {
        $e = new TimeServer();

        $this->assertEquals('Time Server', $e->getName());
        $this->assertEquals('Build a Time Server!', $e->getDescription());
        $this->assertEquals(ExerciseType::CLI, $e->getType());

        $this->assertFileExists(realpath($e->getProblem()));
    }

    public function testWithNoCode(): void
    {
        $this->runExercise('solution-no-code.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'No code was found');
    }

    public function testFailureWhenCannotConnect(): void
    {
        $this->runExercise('solution-no-server.php');

        $this->assertVerifyWasNotSuccessful();

        $reason  = '/^Client returns an error \(number \d+\): Connection refused';
        $reason .= ' while trying to join tcp:\/\/0\.0\.0\.0:\d+\.$/';

        $this->assertResultsHasFailureAndMatches(Failure::class, function (Failure $failure) use ($reason) {
            $this->assertMatchesRegularExpression($reason, $failure->getReason());

            return true;
        });
    }

    public function testWithIncorrectOutput(): void
    {
        $this->runExercise('solution-wrong-output.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailureAndMatches(ComparisonFailure::class, function (ComparisonFailure $failure) {
            static::assertMatchesRegularExpression(
                '/^\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}\\n$/',
                $failure->getExpectedValue()
            );
            static::assertMatchesRegularExpression(
                '/^\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}\\n$/',
                $failure->getActualValue()
            );

            return true;
        });
    }

    public function testWithCorrectSolution(): void
    {
        $this->runExercise('solution-correct.php');

        $this->assertVerifyWasSuccessful();
    }
}
