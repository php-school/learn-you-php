<?php

namespace PhpSchool\LearnYouPhpTest\Exercise;

use Faker\Factory;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use PhpSchool\LearnYouPhp\Exercise\ConcernedAboutSeparation;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\Result\FailureInterface;
use PhpSchool\PhpWorkshop\Result\Success;
use PhpSchool\PhpWorkshop\Solution\SolutionInterface;
use PHPUnit_Framework_TestCase;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class ConcernedAboutSeparationTest
 * @package PhpSchool\LearnYouPhpTest\Exercise
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class ConcernedAboutSeparationTest extends PHPUnit_Framework_TestCase
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
     * @var Parser
     */
    private $parser;

    public function setUp()
    {
        $this->filesystem = new Filesystem;
        $this->faker = Factory::create();
        $this->parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
    }

    public function testConcernedAboutSeparationExercise()
    {
        $e = new ConcernedAboutSeparation($this->filesystem, $this->faker, $this->parser);
        $this->assertEquals('Concerned about Separation?', $e->getName());
        $this->assertEquals('Separate code and utilise files and classes', $e->getDescription());
        $this->assertEquals(ExerciseType::CLI, $e->getType());

        $this->assertInstanceOf(SolutionInterface::class, $e->getSolution());
        $this->assertFileExists(realpath($e->getProblem()));
        $this->assertNull($e->tearDown());
    }

    public function testGetArgsCreatesFilesAndReturnsRandomExt()
    {
        $e = new ConcernedAboutSeparation($this->filesystem, $this->faker, $this->parser);
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
        $e = new ConcernedAboutSeparation($this->filesystem, $this->faker, $this->parser);
        $args = $e->getArgs();
        $path = $args[0];
        $this->assertFileExists($path);

        $e->tearDown();

        $this->assertFileNotExists($path);
    }

    public function testCheckReturnsFailureIfNoIncludeFoundInSolution()
    {
        $e = new ConcernedAboutSeparation($this->filesystem, $this->faker, $this->parser);
        $failure = $e->check(__DIR__ . '/../res/concerned-about-separation/no-include.php');
        
        $this->assertInstanceOf(Failure::class, $failure);
        $this->assertEquals('No require statement found', $failure->getReason());
        $this->assertEquals('Concerned about Separation?', $failure->getCheckName());
    }

    public function testCheckReturnsSuccessIfIncludeFound()
    {
        $e = new ConcernedAboutSeparation($this->filesystem, $this->faker, $this->parser);
        $success = $e->check(__DIR__ . '/../res/concerned-about-separation/include.php');

        $this->assertInstanceOf(Success::class, $success);
        $this->assertEquals('Concerned about Separation?', $success->getCheckName());
    }
}
