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

/**
 * Class MyFirstIo
 * @package PhpSchool\LearnYouPhp\Exercise
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
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
    public function getName()
    {
        return 'My First IO';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Read a file from the file system';
    }

    /**
     * @return array
     */
    public function getArgs()
    {
        $path = $this->getTemporaryPath();
        $paragraphs = $this->faker->paragraphs(rand(5, 50), true);
        $this->filesystem->dumpFile($path, $paragraphs);

        return [$path];
    }

    /**
     * @return null
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
        return ['file_get_contents'];
    }

    /**
     * @return string[]
     */
    public function getBannedFunctions()
    {
        return ['file'];
    }

    /**
     * @return ExerciseType
     */
    public function getType()
    {
        return ExerciseType::CLI();
    }

    /**
     * @param ExerciseDispatcher $dispatcher
     */
    public function configure(ExerciseDispatcher $dispatcher)
    {
        $dispatcher->requireCheck(FunctionRequirementsCheck::class, $dispatcher::CHECK_AFTER);
    }
}
