<?php

namespace PhpSchool\LearnYouPhpTest\Exercise;

use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\Solution\SolutionInterface;
use PHPUnit\Framework\TestCase;
use PhpSchool\LearnYouPhp\Exercise\BabySteps;

class BabyStepsTest extends TestCase
{
    public function testBabyStepsExercise(): void
    {
        $e = new BabySteps();
        $this->assertEquals('Baby Steps', $e->getName());
        $this->assertEquals('Simple Addition', $e->getDescription());
        $this->assertEquals(ExerciseType::CLI, $e->getType());

        //sometime we don't get any args as number of args is random
        //we need some args for code-coverage, so just try again
        do {
            $args = $e->getArgs()[0];
        } while (empty($args));

        foreach ($args as $arg) {
            $this->assertIsNumeric($arg);
        }

        $this->assertFileExists(realpath($e->getProblem()));
        $this->assertNull($e->tearDown());
    }
}
