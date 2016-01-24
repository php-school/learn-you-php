<?php


namespace PhpSchool\LearnYouPhpTest\Exercise;

use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\Solution\SolutionInterface;
use PHPUnit_Framework_TestCase;
use PhpSchool\LearnYouPhp\Exercise\HelloWorld;

/**
 * Class HelloWorldTest
 * @package PhpSchool\LearnYouPhpTest\Exercise
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class HelloWorldTest extends PHPUnit_Framework_TestCase
{
    public function testHelloWorldExercise()
    {
        $e = new HelloWorld;
        $this->assertEquals('Hello World', $e->getName());
        $this->assertEquals('Simple Hello World exercise', $e->getDescription());
        $this->assertEquals(ExerciseType::CLI, $e->getType());

        $this->assertEquals([], $e->getArgs());

        $this->assertInstanceOf(SolutionInterface::class, $e->getSolution());
        $this->assertFileExists(realpath($e->getProblem()));
        $this->assertNull($e->tearDown());
    }
}
