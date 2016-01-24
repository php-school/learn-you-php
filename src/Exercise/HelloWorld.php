<?php

namespace PhpSchool\LearnYouPhp\Exercise;

use PhpSchool\PhpWorkshop\Exercise\AbstractExercise;
use PhpSchool\PhpWorkshop\Exercise\CliExercise;
use PhpSchool\PhpWorkshop\Exercise\ExerciseInterface;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\ExerciseCheck\StdOutExerciseCheck;
use PhpSchool\PhpWorkshop\ExerciseDispatcher;

/**
 * Class HelloWorld
 * @package PhpSchool\LearnYouPhp\Exercise
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class HelloWorld extends AbstractExercise implements ExerciseInterface, CliExercise
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
     * @return array
     */
    public function getArgs()
    {
        return [];
    }

    /**
     * @return ExerciseType
     */
    public function getType()
    {
        return ExerciseType::CLI();
    }
}
