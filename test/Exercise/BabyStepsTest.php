<?php


namespace PhpSchool\LearnYouPhpTest\Exercise;

use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\Solution\SolutionInterface;
use PHPUnit_Framework_TestCase;
use PhpSchool\LearnYouPhp\Exercise\BabySteps;

/**
 * Class BabyStepsTest
 * @package PhpSchool\LearnYouPhpTest\Exercise
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class BabyStepsTest extends PHPUnit_Framework_TestCase
{
    public function testHelloWorldExercise()
    {
        $e = new BabySteps;
        $this->assertEquals('Baby Steps', $e->getName());
        $this->assertEquals('Simple Addition', $e->getDescription());
        $this->assertEquals(ExerciseType::CLI, $e->getType());

        //sometime we don't get any args as number of args is random
        //we need some args for code-coverage, so just try again
        do {
            $args = $e->getArgs();
        } while (empty($args));

        foreach ($args as $arg) {
            $this->assertInternalType('int', $arg);
        }

        $this->assertInstanceOf(SolutionInterface::class, $e->getSolution());
        $this->assertFileExists(realpath($e->getProblem()));
        $this->assertNull($e->tearDown());
    }
}
