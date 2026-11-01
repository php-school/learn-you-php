<?php

namespace PhpSchool\LearnYouPhpTest\Exercise;

use Faker\Factory;
use Faker\Generator;
use PhpSchool\PhpWorkshop\Application;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\Result\FunctionRequirementsFailure;
use PhpSchool\PhpWorkshop\TestUtils\WorkshopExerciseTest;
use PhpSchool\LearnYouPhp\Exercise\MyFirstIo;

class MyFirstIoTest extends WorkshopExerciseTest
{
    private Generator $faker;

    public function setUp(): void
    {
        $this->faker = Factory::create();
        parent::setUp();
    }

    public function getApplication(): Application
    {
        return require __DIR__ . '/../../app/bootstrap.php';
    }

    public function getExerciseClass(): string
    {
        return MyFirstIo::class;
    }

    public function testExerciseMeta(): void
    {
        $e = new MyFirstIo($this->faker);
        $this->assertEquals('My First IO', $e->getName());
        $this->assertEquals('Read a file from the file system', $e->getDescription());
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
        $this->runExercise('wrong-function-requirements.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertOutputWasCorrect();

        $this->assertResultsHasFailureAndMatches(
            FunctionRequirementsFailure::class,
            function (FunctionRequirementsFailure $failure) {
                self::assertEquals(['file_get_contents'], $failure->getMissingFunctions());
                self::assertEquals([['function' => 'file', 'line' => 3]], $failure->getBannedFunctions());

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
