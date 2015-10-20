<?php

namespace PhpSchool\LearnYouPhp\Exercise;

use PhpSchool\PhpWorkshop\Exercise\ExerciseInterface;
use PhpSchool\PhpWorkshop\ExerciseCheck\StdOutExerciseCheck;

/**
 * Class BabySteps
 * @package PhpSchool\LearnYouPhp\Exercise
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class BabySteps implements ExerciseInterface, StdOutExerciseCheck
{

    /**
     * @return string
     */
    public function getName()
    {
        return 'Baby Steps';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Simple Addition';
    }

    /**
     * @return string
     */
    public function getSolution()
    {
        return __DIR__ . '/../../res/solutions/baby-steps/solution.php';
    }

    /**
     * @return string
     */
    public function getProblem()
    {
        return __DIR__ . '/../../res/problems/baby-steps/problem.md';
    }

    /**
     * @return array
     */
    public function getArgs()
    {
        $numArgs = rand(0, 10);

        $args = [];
        for ($i = 0; $i < $numArgs; $i ++) {
            $args[] = rand(0, 100);
        }

        return $args;
    }

    /**
     * @return void
     */
    public function tearDown()
    {
    }
}
