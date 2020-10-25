<?php

namespace PhpSchool\LearnYouPhpTest\Exercise;

use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\Solution\SolutionInterface;
use PHPUnit\Framework\TestCase;
use PhpSchool\LearnYouPhp\Exercise\HelloWorld;

class HelloWorldTest extends TestCase
{
    public function testHelloWorldExercise(): void
    {
        $e = new HelloWorld();
        $this->assertEquals('Hello World', $e->getName());
        $this->assertEquals('Simple Hello World exercise', $e->getDescription());
        $this->assertEquals(ExerciseType::CLI, $e->getType());

        $this->assertEquals([], $e->getArgs()[0]);

        $this->assertFileExists(realpath($e->getProblem()));
    }
}
