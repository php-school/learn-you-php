<?php


namespace PhpSchool\LearnYouPhpTest\Exercise;

use Faker\Factory;
use Faker\Generator;
use PhpSchool\LearnYouPhp\Exercise\ExceptionalCoding;
use PhpSchool\PhpWorkshop\Check\FunctionRequirementsCheck;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\ExerciseDispatcher;
use PhpSchool\PhpWorkshop\Solution\SolutionInterface;
use PHPUnit_Framework_TestCase;
use PhpSchool\LearnYouPhp\Exercise\MyFirstIo;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class ExceptionalCodingTest
 * @package PhpSchool\LearnYouPhpTest\Exercise
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
class ExceptionalCodingTest extends PHPUnit_Framework_TestCase
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
        $e = new ExceptionalCoding($this->filesystem, $this->faker);
        $this->assertEquals('Exceptional Coding', $e->getName());
        $this->assertEquals('Introduction to Exceptions', $e->getDescription());
        $this->assertEquals(ExerciseType::CLI, $e->getType());

        $this->assertInstanceOf(SolutionInterface::class, $e->getSolution());
        $this->assertFileExists(realpath($e->getProblem()));
        $this->assertNull($e->tearDown());
    }

    public function testGetArgsCreateAtleastOneExistingFile()
    {
        $e = new ExceptionalCoding($this->filesystem, $this->faker);
        $args = $e->getArgs();

        $existingFiles = array_filter($args, 'file_exists');

        foreach ($existingFiles as $file) {
            $this->assertFileExists($file);
        }

        $this->assertGreaterThanOrEqual(1, count($existingFiles));
    }

    public function testGetArgsHasAtleastOneNonExistingFile()
    {
        $e = new ExceptionalCoding($this->filesystem, $this->faker);
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
        $e = new ExceptionalCoding($this->filesystem, $this->faker);
        $args = $e->getArgs();

        $existingFiles = array_filter($args, 'file_exists');

        $this->assertFileExists($existingFiles[0]);

        $e->tearDown();

        $this->assertFileNotExists($existingFiles[0]);
    }

    public function testFunctionRequirements()
    {
        $e = new ExceptionalCoding($this->filesystem, $this->faker);
        $this->assertEquals([], $e->getRequiredFunctions());
        $this->assertEquals(['array_filter', 'file_exists'], $e->getBannedFunctions());
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

        $e = new ExceptionalCoding($this->filesystem, $this->faker);
        $e->configure($dispatcher);
    }
}
