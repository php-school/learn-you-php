<?php

namespace PhpSchool\LearnYouPhp\Exercise;

use Faker\Generator;
use PhpSchool\PhpWorkshop\Check\FunctionRequirementsCheck;
use PhpSchool\PhpWorkshop\Exercise\AbstractExercise;
use PhpSchool\PhpWorkshop\Exercise\CliExercise;
use PhpSchool\PhpWorkshop\Exercise\ExerciseInterface;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\Exercise\TemporaryDirectoryTrait;
use PhpSchool\PhpWorkshop\ExerciseCheck\FunctionRequirementsExerciseCheck;
use PhpSchool\PhpWorkshop\ExerciseCheck\StdOutExerciseCheck;
use PhpSchool\PhpWorkshop\ExerciseDispatcher;
use Symfony\Component\Filesystem\Filesystem;

class ExceptionalCoding extends AbstractExercise implements
    ExerciseInterface,
    CliExercise,
    FunctionRequirementsExerciseCheck
{
    use TemporaryDirectoryTrait;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var Generator
     */
    private $faker;

    public function __construct(Filesystem $filesystem, Generator $faker)
    {
        $this->filesystem = $filesystem;
        $this->faker = $faker;
    }

    public function getName(): string
    {
        return "Exceptional Coding";
    }

    public function getDescription(): string
    {
        return "Introduction to Exceptions";
    }

    /**
     * @inheritdoc
     */
    public function getArgs(): array
    {
        $this->filesystem->mkdir($this->getTemporaryPath());

        $fileCount = rand(2, 10);
        $realFiles = rand(1, $fileCount - 1);

        $files = [];
        foreach (range(1, $fileCount) as $index) {
            $file = sprintf('%s/%s.txt', $this->getTemporaryPath(), $this->faker->uuid());
            if ($index <= $realFiles) {
                $this->filesystem->touch($file);
            }
            $files[] = $file;
        }

        return [$files];
    }

    public function tearDown(): void
    {
        $this->filesystem->remove($this->getTemporaryPath());
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

    public function configure(ExerciseDispatcher $dispatcher): void
    {
        $dispatcher->requireCheck(FunctionRequirementsCheck::class);
    }
}
