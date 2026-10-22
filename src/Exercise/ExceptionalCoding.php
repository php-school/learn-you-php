<?php

namespace PhpSchool\LearnYouPhp\Exercise;

use Faker\Generator;
use PhpSchool\PhpWorkshop\Check\FunctionRequirementsCheck;
use PhpSchool\PhpWorkshop\Exercise\AbstractExercise;
use PhpSchool\PhpWorkshop\Exercise\CliExercise;
use PhpSchool\PhpWorkshop\Exercise\ExerciseInterface;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\Exercise\Scenario\CliScenario;
use PhpSchool\PhpWorkshop\ExerciseCheck\FunctionRequirementsExerciseCheck;

class ExceptionalCoding extends AbstractExercise implements
    ExerciseInterface,
    CliExercise,
    FunctionRequirementsExerciseCheck
{
    public function __construct(private Generator $faker)
    {
    }

    public function getName(): string
    {
        return "Exceptional Coding";
    }

    public function getDescription(): string
    {
        return "Introduction to Exceptions";
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
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getBannedFunctions(): array
    {
        return ['array_filter', 'file_exists'];
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
