<?php

namespace PhpSchool\LearnYouPhpTest\Exercise;

use Faker\Factory;
use Faker\Generator;
use PhpSchool\LearnYouPhp\Exercise\ExceptionalCoding;
use PhpSchool\PhpWorkshop\Application;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\Result\FunctionRequirementsFailure;
use PhpSchool\PhpWorkshop\TestUtils\WorkshopExerciseTest;

class ExceptionalCodingTest extends WorkshopExerciseTest
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
        return ExceptionalCoding::class;
    }

    public function testExerciseMeta(): void
    {
        $e = new ExceptionalCoding($this->faker);
        $this->assertEquals('Exceptional Coding', $e->getName());
        $this->assertEquals('Introduction to Exceptions', $e->getDescription());
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
        $this->runExercise('solution-banned-functions.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertOutputWasCorrect();

        $this->assertResultsHasFailureAndMatches(
            FunctionRequirementsFailure::class,
            function (FunctionRequirementsFailure $failure) {
                self::assertEquals([['function' => 'file_exists', 'line' => 7]], $failure->getBannedFunctions());

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
