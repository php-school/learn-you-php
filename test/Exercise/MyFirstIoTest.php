<?php


namespace PhpSchool\LearnYouPhpTest\Exercise;

use Faker\Factory;
use Faker\Generator;
use PhpSchool\PhpWorkshop\Check\FunctionRequirementsCheck;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\ExerciseDispatcher;
use PhpSchool\PhpWorkshop\Solution\SolutionInterface;
use PHPUnit_Framework_TestCase;
use PhpSchool\LearnYouPhp\Exercise\MyFirstIo;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class MyFirstIoTest
 * @package PhpSchool\LearnYouPhpTest\Exercise
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class MyFirstIoTest extends PHPUnit_Framework_TestCase
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

    public function testMyFirstIoExercise()
    {
        $e = new MyFirstIo($this->filesystem, $this->faker);
        $this->assertEquals('My First IO', $e->getName());
        $this->assertEquals('Read a file from the file system', $e->getDescription());
        $this->assertEquals(ExerciseType::CLI, $e->getType());

        $this->assertInstanceOf(SolutionInterface::class, $e->getSolution());
        $this->assertFileExists(realpath($e->getProblem()));
        $this->assertNull($e->tearDown());
    }

    public function testGetArgsCreatesFileWithRandomContentFromFake()
    {

        $e = new MyFirstIo($this->filesystem, $this->faker);
        $args = $e->getArgs();
        $path = $args[0];
        $this->assertFileExists($path);

        $content1 = file_get_contents($path);
        unlink($path);

        $args = $e->getArgs();
        $path = $args[0];
        $this->assertFileExists($path);

        $content2 = file_get_contents($path);
        $this->assertNotEquals($content1, $content2);
    }

    public function testTearDownRemovesFile()
    {
        $e = new MyFirstIo($this->filesystem, $this->faker);
        $args = $e->getArgs();
        $path = $args[0];
        $this->assertFileExists($path);

        $e->tearDown();

        $this->assertFileNotExists($path);
    }

    public function testFunctionRequirements()
    {
        $e = new MyFirstIo($this->filesystem, $this->faker);
        $this->assertEquals(['file_get_contents'], $e->getRequiredFunctions());
        $this->assertEquals(['file'], $e->getBannedFunctions());
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

        $e = new MyFirstIo($this->filesystem, $this->faker);
        $e->configure($dispatcher);
    }
}
