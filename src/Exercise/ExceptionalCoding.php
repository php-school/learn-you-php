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

    /**
     * @param Filesystem $filesystem
     * @param Generator $faker
     */
    public function __construct(Filesystem $filesystem, Generator $faker)
    {
        $this->filesystem   = $filesystem;
        $this->faker        = $faker;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return "Exceptional Coding";
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return "Introduction to Exceptions";
    }

    /**
     * @return array<array<string>>
     */
    public function getArgs(): array
    {
        $this->filesystem->mkdir($this->getTemporaryPath());

        $fileCount = rand(2, 10);
        $realFiles = rand(1, $fileCount - 1);

        $files = [];
        foreach (range(1, $fileCount) as $index) {
            $file = sprintf('%s/%s.txt', $this->getTemporaryPath(), $this->faker->uuid);
            if ($index <= $realFiles) {
                $this->filesystem->touch($file);
            }
            $files[] = $file;
        }

        return [$files];
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
        $this->filesystem->remove($this->getTemporaryPath());
    }

    /**
     * @return string[]
     */
    public function getRequiredFunctions(): array
    {
        return [];
    }

    /**
     * @return string[]
     */
    public function getBannedFunctions(): array
    {
        return ['array_filter', 'file_exists'];
    }

    /**
     * @return ExerciseType
     */
    public function getType(): ExerciseType
    {
        return new ExerciseType(ExerciseType::CLI);
    }

    /**
     * @param ExerciseDispatcher $dispatcher
     */
    public function configure(ExerciseDispatcher $dispatcher): void
    {
        $dispatcher->requireCheck(FunctionRequirementsCheck::class);
    }
}
