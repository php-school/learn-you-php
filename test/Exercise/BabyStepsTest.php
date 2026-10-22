<?php

namespace PhpSchool\LearnYouPhpTest\Exercise;

use PhpSchool\PhpWorkshop\Application;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\TestUtils\WorkshopExerciseTest;
use PHPUnit\Framework\TestCase;
use PhpSchool\LearnYouPhp\Exercise\BabySteps;

class BabyStepsTest extends WorkshopExerciseTest
{
    public function getApplication(): Application
    {
        return require __DIR__ . '/../../app/bootstrap.php';
    }

    public function getExerciseClass(): string
    {
        return BabySteps::class;
    }

    public function testExerciseMeta(): void
    {
        $e = new BabySteps();
        $this->assertEquals('Baby Steps', $e->getName());
        $this->assertEquals('Simple Addition', $e->getDescription());
        $this->assertEquals(ExerciseType::CLI, $e->getType());
        $this->assertFileExists(realpath($e->getProblem()));
    }

    public function testWithNoCode(): void
    {
        $this->runExercise('solution-no-code.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'No code was found');
    }

    public function testWithIncorrectOutput(): void
    {
        $this->runExercise('solution-wrong-output.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertOutputWasIncorrect();
    }

    public function testWithCorrectSolution(): void
    {
        $this->runExercise('solution-correct.php');

        $this->assertVerifyWasSuccessful();
    }
}
