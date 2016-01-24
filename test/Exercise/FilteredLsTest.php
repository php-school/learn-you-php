<?php


namespace PhpSchool\LearnYouPhpTest\Exercise;

use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\Solution\SolutionInterface;
use PHPUnit_Framework_TestCase;
use PhpSchool\LearnYouPhp\Exercise\FilteredLs;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class FilteredLsTest
 * @package PhpSchool\LearnYouPhpTest\Exercise
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class FilteredLsTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Filesystem
     */
    private $filesystem;

    public function setUp()
    {
        $this->filesystem = new Filesystem;
    }

    public function testFilteredLsExercise()
    {
        $e = new FilteredLs($this->filesystem);
        $this->assertEquals('Filtered LS', $e->getName());
        $this->assertEquals('Read files in a folder and filter by a given extension', $e->getDescription());
        $this->assertEquals(ExerciseType::CLI, $e->getType());

        $this->assertInstanceOf(SolutionInterface::class, $e->getSolution());
        $this->assertFileExists(realpath($e->getProblem()));
        $this->assertNull($e->tearDown());
    }

    public function testGetArgsCreatesFilesAndReturnsRandomExt()
    {
        $e = new FilteredLs($this->filesystem);
        $args = $e->getArgs();
        $path = $args[0];
        $this->assertFileExists($path);

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

        array_walk($files, function ($file) use ($path) {
            $this->assertFileExists(sprintf('%s/%s', $path, $file));
        });

        $extensions = array_unique(array_map(function ($file) {
            return pathinfo($file, PATHINFO_EXTENSION);
        }, $files));

        $this->assertTrue(in_array($args[1], $extensions));
    }

    public function testTearDownRemovesFile()
    {
        $e = new FilteredLs($this->filesystem);
        $args = $e->getArgs();
        $path = $args[0];
        $this->assertFileExists($path);

        $e->tearDown();

        $this->assertFileNotExists($path);
    }
}
