<?php

namespace PhpSchool\LearnYouPhp\Exercise;

use PhpSchool\PhpWorkshop\Exercise\ExerciseInterface;
use PhpSchool\PhpWorkshop\ExerciseCheck\StdOutExerciseCheck;

/**
 * Class HelloWorld
 * @package PhpSchool\LearnYouPhp\Exercise
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class HelloWorld implements ExerciseInterface, StdOutExerciseCheck
{

    /**
     * @return string
     */
    public function getName()
    {
        return 'Hello World';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Simple Hello World exercise';
    }

    /**
     * @return string
     */
    public function getSolution()
    {
        return __DIR__ . '/../../res/solutions/hello-world/solution.php';
    }

    /**
     * @return string
     */
    public function getProblem()
    {
        return __DIR__ . '/../../res/problems/hello-world/problem.md';
    }

    /**
     * @return array
     */
    public function getArgs()
    {
        return [];
    }

    /**
     * @return void
     */
    public function tearDown()
    {
    }
}
