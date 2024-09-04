<?php

namespace PhpSchool\LearnYouPhpTest\Exercise;

use Faker\Factory;
use PhpSchool\LearnYouPhp\Exercise\ArrayWeGo;
use PhpSchool\PhpWorkshop\Application;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\Result\FunctionRequirementsFailure;
use PhpSchool\PhpWorkshop\TestUtils\WorkshopExerciseTest;

class ArrayWeGoTest extends WorkshopExerciseTest
{
    public function getApplication(): Application
    {
        return require __DIR__ . '/../../app/bootstrap.php';
    }

    public function getExerciseClass(): string
    {
        return ArrayWeGo::class;
    }

    public function testExerciseMeta(): void
    {
        $e = new ArrayWeGo(Factory::create());
        $this->assertEquals('Array We Go!', $e->getName());
        $this->assertEquals('Filter an array of file paths and map to SplFile objects', $e->getDescription());
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

    public function testFailureWhenNotUsingRequiredFunctions(): void
    {
        $this->runExercise('no-required-functions.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertOutputWasCorrect();

        $this->assertResultsHasFailureAndMatches(
            FunctionRequirementsFailure::class,
            function (FunctionRequirementsFailure $failure) {
                self::assertEquals(['array_shift', 'array_filter', 'array_map'], $failure->getMissingFunctions());

                return true;
            }
        );
    }

    public function testWithCorrectSolution(): void
    {
        $this->runExercise('solution-correct.php');

        $this->assertVerifyWasSuccessful();
    }
}
