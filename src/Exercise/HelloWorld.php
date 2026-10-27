<?php

namespace PhpSchool\LearnYouPhp\Exercise;

use PhpSchool\PhpWorkshop\Exercise\AbstractExercise;
use PhpSchool\PhpWorkshop\Exercise\CliExercise;
use PhpSchool\PhpWorkshop\Exercise\ExerciseInterface;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\Exercise\Scenario\CliScenario;

class HelloWorld extends AbstractExercise implements ExerciseInterface, CliExercise
{
    public function getName(): string
    {
        return 'Hello World';
    }

    public function getDescription(): string
    {
        return 'Simple Hello World exercise';
    }

    public function defineTestScenario(): CliScenario
    {
        return (new CliScenario())->withExecution();
    }

    public function getType(): ExerciseType
    {
        return new ExerciseType(ExerciseType::CLI);
    }
}
