<?php

namespace PhpSchool\LearnYouPhp\Exercise;

use PhpSchool\PhpWorkshop\Exercise\AbstractExercise;
use PhpSchool\PhpWorkshop\Exercise\CliExercise;
use PhpSchool\PhpWorkshop\Exercise\ExerciseInterface;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\Exercise\Scenario\CliScenario;

class FilteredLs extends AbstractExercise implements ExerciseInterface, CliExercise
{
    public function getName(): string
    {
        return 'Filtered LS';
    }

    public function getDescription(): string
    {
        return 'Read files in a folder and filter by a given extension';
    }

    public function defineTestScenario(): CliScenario
    {
        $files = [
            "learnyouphp.dat",
            "learnyouphp.txt",
            "learnyouphp.sql",
            "txt",
            "sql",
            "api.html",
            "html",
            "README.md",
            "CHANGELOG.md",
            "LICENCE.md",
            "md",
            "data.json",
            "json",
            "data.dat",
            "words.dat",
            "w00t.dat",
            "w00t.txt",
            "wrrrrongdat",
            "dat",
        ];

        $ext = '';
        while ($ext === '') {
            $index = array_rand($files);
            $ext = pathinfo($files[$index], PATHINFO_EXTENSION);
        }

        $scenario = (new CliScenario())
            ->withExecution(['files', $ext]);

        array_walk($files, function (string $file) use ($scenario) {
            $scenario->withFile('files/' . $file, '');
        });

        return $scenario;
    }

    public function getType(): ExerciseType
    {
        return new ExerciseType(ExerciseType::CLI);
    }
}
