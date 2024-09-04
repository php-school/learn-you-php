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

class MyFirstIo extends AbstractExercise implements
    ExerciseInterface,
    CliExercise,
    FunctionRequirementsExerciseCheck
{
    public function __construct(private Generator $faker)
    {
    }

    public function getName(): string
    {
        return 'My First IO';
    }

    public function getDescription(): string
    {
        return 'Read a file from the file system';
    }

    public function defineTestScenario(): CliScenario
    {
        $filename = bin2hex(random_bytes(4)) . '.txt';
        $paragraphs = implode("\n\n", (array) $this->faker->paragraphs(rand(5, 50)));

        return (new CliScenario())
            ->withExecution([$filename])
            ->withFile($filename, $paragraphs);
    }

    /**
     * @inheritdoc
     */
    public function getRequiredFunctions(): array
    {
        return ['file_get_contents'];
    }

    /**
     * @inheritdoc
     */
    public function getBannedFunctions(): array
    {
        return ['file'];
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
