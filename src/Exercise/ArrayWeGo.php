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

class ArrayWeGo extends AbstractExercise implements ExerciseInterface, FunctionRequirementsExerciseCheck, CliExercise
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
        return 'Array We Go!';
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Filter an array of file paths and map to SplFile objects';
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

    public function tearDown(): void
    {
        $this->filesystem->remove($this->getTemporaryPath());
    }

    /**
     * @return string[]
     */
    public function getRequiredFunctions(): array
    {
        return ['array_shift', 'array_filter', 'array_map'];
    }

    /**
     * @return string[]
     */
    public function getBannedFunctions(): array
    {
        return ['basename'];
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
