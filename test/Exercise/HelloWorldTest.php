<?php

namespace PhpSchool\LearnYouPhpTest\Exercise;

use PhpSchool\PhpWorkshop\Application;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\TestUtils\WorkshopExerciseTest;
use PhpSchool\LearnYouPhp\Exercise\HelloWorld;

class HelloWorldTest extends WorkshopExerciseTest
{
    public function getApplication(): Application
    {
        return require __DIR__ . '/../../app/bootstrap.php';
    }

    public function getExerciseClass(): string
    {
        return HelloWorld::class;
    }

    public function testExerciseMeta(): void
    {
        $e = new HelloWorld();
        $this->assertEquals('Hello World', $e->getName());
        $this->assertEquals('Simple Hello World exercise', $e->getDescription());
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
