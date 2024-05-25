<?php

namespace PhpSchool\LearnYouPhpTest\Exercise;

use PhpParser\ParserFactory;
use PhpSchool\LearnYouPhp\Exercise\ConcernedAboutSeparation;
use PhpSchool\PhpWorkshop\Application;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\TestUtils\WorkshopExerciseTest;

class ConcernedAboutSeparationTest extends WorkshopExerciseTest
{
    public function getApplication(): Application
    {
        return require __DIR__ . '/../../app/bootstrap.php';
    }

    public function getExerciseClass(): string
    {
        return ConcernedAboutSeparation::class;
    }

    public function testExerciseMeta(): void
    {
        $e = new ConcernedAboutSeparation((new ParserFactory())->create(ParserFactory::PREFER_PHP7));
        $this->assertEquals('Concerned about Separation?', $e->getName());
        $this->assertEquals('Separate code and utilise files and classes', $e->getDescription());
        $this->assertEquals(ExerciseType::CLI, $e->getType());

        $this->assertFileExists(realpath($e->getProblem()));
    }

    public function testWithNoCode(): void
    {
        $this->runExercise('solution-no-code.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'No code was found');
    }

    public function testFailureWhenNotUsingInclude(): void
    {
        $this->runExercise('no-include.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(
            Failure::class,
            'No require statement found'
        );
    }

    public function testWithCorrectSolution(): void
    {
        $this->runExercise('correct/solution.php', self::DIRECTORY_SOLUTION);

        $this->assertVerifyWasSuccessful();
    }
}
