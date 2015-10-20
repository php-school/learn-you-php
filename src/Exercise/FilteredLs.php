<?php

namespace PhpSchool\LearnYouPhp\Exercise;

use PhpSchool\PhpWorkshop\Exercise\ExerciseInterface;
use PhpSchool\PhpWorkshop\ExerciseCheck\StdOutExerciseCheck;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class FilteredLs
 * @package PhpSchool\LearnYouPhp\Exercise
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class FilteredLs implements ExerciseInterface, StdOutExerciseCheck
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem   = $filesystem;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Filtered LS';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Read files in a folder and filter by a given extension';
    }

    /**
     * @return string
     */
    public function getSolution()
    {
        return __DIR__ . '/../../res/solutions/filtered-ls/solution.php';
    }

    /**
     * @return string
     */
    public function getProblem()
    {
        return __DIR__ . '/../../res/problems/filtered-ls/problem.md';
    }

    /**
     * @return array
     */
    public function getArgs()
    {
        $folder = $this->getTemporaryPath();

        $files = [
            "learnyouphp.dat",
            "learnyouphp.txt",
            "learnyouphp.sql",
            "api.html",
            "README.md",
            "CHANGELOG.md",
            "LICENCE.md",
            "md",
            "data.json",
            "data.dat",
            "words.dat",
            "w00t.dat",
            "w00t.txt",
            "wrrrrongdat",
            "dat",
        ];

        $this->filesystem->mkdir($folder);
        array_walk($files, function ($file) use ($folder) {
            $this->filesystem->dumpFile(sprintf('%s/%s', $folder, $file), '');
        });

        $ext = '';
        while ($ext === '') {
            $index = array_rand($files);
            $ext = pathinfo($files[$index], PATHINFO_EXTENSION);
        }

        return [$folder, $ext];
    }

    /**
     * @return null
     */
    public function tearDown()
    {
        $this->filesystem->remove($this->getTemporaryPath());
    }

    /**
     * @return string
     */
    private function getTemporaryPath()
    {
        return sprintf('%s/%s', realpath(sys_get_temp_dir()), str_replace('\\', '_', __CLASS__));
    }
}
