<?php

namespace PhpSchool\LearnYouPhp\Exercise;

use Faker\Generator;
use PhpSchool\PhpWorkshop\Check\FunctionRequirementsCheck;
use PhpSchool\PhpWorkshop\Exercise\AbstractExercise;
use PhpSchool\PhpWorkshop\Exercise\CliExercise;
use PhpSchool\PhpWorkshop\Exercise\ExerciseInterface;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\Exercise\Scenario\CliScenario;
use PhpSchool\PhpWorkshop\Exercise\TemporaryDirectoryTrait;
use PhpSchool\PhpWorkshop\ExerciseCheck\FunctionRequirementsExerciseCheck;
use PhpSchool\PhpWorkshop\ExerciseDispatcher;
use Symfony\Component\Filesystem\Filesystem;

class ArrayWeGo extends AbstractExercise implements ExerciseInterface, FunctionRequirementsExerciseCheck, CliExercise
{
    public function __construct(private Generator $faker)
    {
    }

    public function getName(): string
    {
        return 'Array We Go!';
    }

    public function getDescription(): string
    {
        return 'Filter an array of file paths and map to SplFile objects';
    }

    public function defineTestScenario(): CliScenario
    {
        $fileCount = rand(2, 10);
        $realFileCount = rand(1, $fileCount - 1);

        $files = [];
        $realFiles = [];
        foreach (range(1, $fileCount) as $index) {
            $file = $this->faker->uuid() . ".txt";
            if ($index <= $realFileCount) {
                $realFiles[] = $file;
            }
            $files[] = $file;
        }

        $scenario = (new CliScenario())
            ->withExecution($files);

        foreach ($realFiles as $realFile) {
            $scenario->withFile($realFile, '');
        }

        return $scenario;
    }

    /**
     * @inheritdoc
     */
    public function getRequiredFunctions(): array
    {
        return ['array_shift', 'array_filter', 'array_map'];
    }

    /**
     * @inheritdoc
     */
    public function getBannedFunctions(): array
    {
        return ['basename'];
    }

    public function getType(): ExerciseType
    {
        return new ExerciseType(ExerciseType::CLI);
    }

    public function getRequiredChecks(): array
    {
        return [FunctionRequirementsCheck::class];
    }
}
