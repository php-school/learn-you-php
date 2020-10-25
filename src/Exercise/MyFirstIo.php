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

class MyFirstIo extends AbstractExercise implements
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
        return 'My First IO';
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Read a file from the file system';
    }

    /**
     * @return array<array<string>>
     */
    public function getArgs(): array
    {
        $path = $this->getTemporaryPath();
        $paragraphs = implode("\n\n", (array) $this->faker->paragraphs(rand(5, 50)));
        $this->filesystem->dumpFile($path, $paragraphs);

        return [[$path]];
    }

    public function tearDown(): void
    {
        $this->filesystem->remove($this->getTemporaryPath());
    }

    /**
     * @return array<string>
     */
    public function getRequiredFunctions(): array
    {
        return ['file_get_contents'];
    }

    /**
     * @return array<string>
     */
    public function getBannedFunctions(): array
    {
        return ['file'];
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
