<?php

namespace PhpSchool\LearnYouPhp\Exercise;

use PhpSchool\PhpWorkshop\Exercise\AbstractExercise;
use PhpSchool\PhpWorkshop\Exercise\CliExercise;
use PhpSchool\PhpWorkshop\Exercise\ExerciseInterface;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\ExerciseCheck\StdOutExerciseCheck;

class BabySteps extends AbstractExercise implements ExerciseInterface, CliExercise
{
    public function getName(): string
    {
        return 'Baby Steps';
    }

    public function getDescription(): string
    {
        return 'Simple Addition';
    }

    /**
     * @inheritdoc
     */
    public function getArgs(): array
    {
        $numArgs = rand(0, 10);

        $args = [];
        for ($i = 0; $i < $numArgs; $i++) {
            $args[] = (string) rand(0, 100);
        }

        return [$args];
    }

    public function getType(): ExerciseType
    {
        return new ExerciseType(ExerciseType::CLI);
    }
}
