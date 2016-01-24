<?php


namespace PhpSchool\LearnYouPhpTest\Exercise;

use Faker\Factory;
use Faker\Generator;
use PhpSchool\LearnYouPhp\Exercise\ArrayWeGo;
use PhpSchool\PhpWorkshop\Check\FunctionRequirementsCheck;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\ExerciseDispatcher;
use PhpSchool\PhpWorkshop\Solution\SolutionInterface;
use PHPUnit_Framework_TestCase;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class ArrayWeGoTest
 * @package PhpSchool\LearnYouPhpTest\Exercise
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
class ArrayWeGoTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Generator
     */
    private $faker;

    /**
     * @var Filesystem
     */
    private $filesystem;

    public function setUp()
    {
        $this->faker = Factory::create();
        $this->filesystem = new Filesystem;
    }

    public function testArrWeGoExercise()
    {
        $e = new ArrayWeGo($this->filesystem, $this->faker);
        $this->assertEquals('Array We Go!', $e->getName());
        $this->assertEquals('Filter an array of file paths and map to SplFile objects', $e->getDescription());
        $this->assertEquals(ExerciseType::CLI, $e->getType());
        
        $this->assertInstanceOf(SolutionInterface::class, $e->getSolution());
        $this->assertFileExists(realpath($e->getProblem()));
        $this->assertNull($e->tearDown());
    }

    public function testGetArgsCreateAtLeastOneExistingFile()
    {
        $e = new ArrayWeGo($this->filesystem, $this->faker);
        $args = $e->getArgs();

        $existingFiles = array_filter($args, 'file_exists');

        foreach ($existingFiles as $file) {
            $this->assertFileExists($file);
        }

        $this->assertGreaterThanOrEqual(1, count($existingFiles));
    }

    public function testGetArgsHasAtLeastOneNonExistingFile()
    {
        $e = new ArrayWeGo($this->filesystem, $this->faker);
        $args = $e->getArgs();

        $nonExistingFiles = array_filter($args, function ($arg) {
            return !file_exists($arg);
        });

        foreach ($nonExistingFiles as $file) {
            $this->assertFileNotExists($file);
        }

        $this->assertGreaterThanOrEqual(1, count($nonExistingFiles));
    }

    public function testTearDownRemovesFile()
    {
        $e = new ArrayWeGo($this->filesystem, $this->faker);
        $args = $e->getArgs();

        $existingFiles = array_filter($args, 'file_exists');

        $this->assertFileExists($existingFiles[0]);

        $e->tearDown();

        $this->assertFileNotExists($existingFiles[0]);
    }

    public function testFunctionRequirements()
    {
        $e = new ArrayWeGo($this->filesystem, $this->faker);
        $this->assertEquals(['array_shift', 'array_filter', 'array_map'], $e->getRequiredFunctions());
        $this->assertEquals(['basename'], $e->getBannedFunctions());
    }

    public function testConfigure()
    {
        $dispatcher = $this->getMockBuilder(ExerciseDispatcher::class)
            ->disableOriginalConstructor()
            ->getMock();

        $dispatcher
            ->expects($this->once())
            ->method('requireCheck')
            ->with(FunctionRequirementsCheck::class);

        $e = new ArrayWeGo($this->filesystem, $this->faker);
        $e->configure($dispatcher);
    }
}
