<?php

namespace PhpSchool\LearnYouPhp\Exercise;

use PhpSchool\PhpWorkshop\Exercise\AbstractExercise;
use PhpSchool\PhpWorkshop\Exercise\CliExercise;
use PhpSchool\PhpWorkshop\Exercise\ExerciseInterface;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\ExerciseCheck\StdOutExerciseCheck;
use PhpSchool\PhpWorkshop\ExerciseDispatcher;

class HelloWorld extends AbstractExercise implements ExerciseInterface, CliExercise
{

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Hello World';
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Simple Hello World exercise';
    }

    /**
     * @return array<array<string>>
     */
    public function getArgs(): array
    {
        return [[]];
    }

    /**
     * @return ExerciseType
     */
    public function getType(): ExerciseType
    {
        return new ExerciseType(ExerciseType::CLI);
    }
}
