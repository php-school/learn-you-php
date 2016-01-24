<?php

namespace PhpSchool\LearnYouPhp\Exercise;

use PhpSchool\PhpWorkshop\Exercise\AbstractExercise;
use PhpSchool\PhpWorkshop\Exercise\CliExercise;
use PhpSchool\PhpWorkshop\Exercise\ExerciseInterface;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\ExerciseCheck\StdOutExerciseCheck;

/**
 * Class BabySteps
 * @package PhpSchool\LearnYouPhp\Exercise
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class BabySteps extends AbstractExercise implements ExerciseInterface, CliExercise
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
     * @return ExerciseType
     */
    public function getType()
    {
        return ExerciseType::CLI();
    }
}
