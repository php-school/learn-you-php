<?php

namespace PhpSchool\LearnYouPhp\Exercise;

use Faker\Generator;
use PhpSchool\PhpWorkshop\Exercise\ExerciseInterface;
use PhpSchool\PhpWorkshop\ExerciseCheck\FunctionRequirementsExerciseCheck;
use PhpSchool\PhpWorkshop\ExerciseCheck\StdOutExerciseCheck;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class MyFirstIo
 * @package PhpSchool\LearnYouPhp\Exercise
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class MyFirstIo implements
    ExerciseInterface,
    StdOutExerciseCheck,
    FunctionRequirementsExerciseCheck
{
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
     * @return string
     */
    public function getSolution()
    {
        return __DIR__ . '/../../res/solutions/my-first-io/solution.php';
    }

    /**
     * @return string
     */
    public function getProblem()
    {
        return __DIR__ . '/../../res/problems/my-first-io/problem.md';
    }

    /**
     * @return string[]
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
     * @return string
     */
    private function getTemporaryPath()
    {
        return sprintf('%s/%s', realpath(sys_get_temp_dir()), str_replace('\\', '_', __CLASS__));
    }
}
