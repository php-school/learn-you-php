<?php

namespace PhpSchool\LearnYouPhp\Exercise;

use Faker\Generator;
use PhpSchool\PhpWorkshop\Exercise\AbstractExercise;
use PhpSchool\PhpWorkshop\Exercise\ExerciseInterface;
use PhpSchool\PhpWorkshop\Exercise\TemporaryDirectoryTrait;
use PhpSchool\PhpWorkshop\ExerciseCheck\FunctionRequirementsExerciseCheck;
use PhpSchool\PhpWorkshop\ExerciseCheck\StdOutExerciseCheck;
use Symfony\Component\Filesystem\Filesystem;

class ExceptionalCoding extends AbstractExercise implements
    ExerciseInterface,
    StdOutExerciseCheck,
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
    public function getName()
    {
        return "Exceptional Coding";
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return "Introduction to Exceptions";
    }

    /**
     * @return array
     */
    public function getArgs()
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

        return $files;
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        $this->filesystem->remove($this->getTemporaryPath());
    }

    /**
     * @return string[]
     */
    public function getRequiredFunctions()
    {
        return [];
    }

    /**
     * @return string[]
     */
    public function getBannedFunctions()
    {
        return ['array_filter', 'file_exists'];
    }
}
