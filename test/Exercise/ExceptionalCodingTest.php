<?php

namespace PhpSchool\LearnYouPhpTest\Exercise;

use Faker\Factory;
use Faker\Generator;
use PhpSchool\LearnYouPhp\Exercise\ExceptionalCoding;
use PhpSchool\PhpWorkshop\Check\FunctionRequirementsCheck;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\ExerciseDispatcher;
use PhpSchool\PhpWorkshop\Solution\SolutionInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
class ExceptionalCodingTest extends TestCase
{

    /**
     * @var Generator
     */
    private $faker;

    /**
     * @var Filesystem
     */
    private $filesystem;

    public function setUp(): void
    {
        $this->faker = Factory::create();
        $this->filesystem = new Filesystem();
    }

    public function testArrWeGoExercise(): void
    {
        $e = new ExceptionalCoding($this->filesystem, $this->faker);
        $this->assertEquals('Exceptional Coding', $e->getName());
        $this->assertEquals('Introduction to Exceptions', $e->getDescription());
        $this->assertEquals(ExerciseType::CLI, $e->getType());

        $this->assertInstanceOf(SolutionInterface::class, $e->getSolution());
        $this->assertFileExists(realpath($e->getProblem()));
        $this->assertNull($e->tearDown());
    }

    public function testGetArgsCreateAtleastOneExistingFile(): void
    {
        $e = new ExceptionalCoding($this->filesystem, $this->faker);
        $args = $e->getArgs();

        $existingFiles = array_filter($args, 'file_exists');

        foreach ($existingFiles as $file) {
            $this->assertFileExists($file);
        }

        $this->assertGreaterThanOrEqual(1, count($existingFiles));
    }

    public function testGetArgsHasAtleastOneNonExistingFile(): void
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

    public function testTearDownRemovesFile(): void
    {
        $e = new ExceptionalCoding($this->filesystem, $this->faker);
        $args = $e->getArgs();

        $existingFiles = array_filter($args, 'file_exists');

        $this->assertFileExists($existingFiles[0]);

        $e->tearDown();

        $this->assertFileNotExists($existingFiles[0]);
    }

    public function testFunctionRequirements(): void
    {
        $e = new ExceptionalCoding($this->filesystem, $this->faker);
        $this->assertEquals([], $e->getRequiredFunctions());
        $this->assertEquals(['array_filter', 'file_exists'], $e->getBannedFunctions());
    }

    public function testConfigure(): void
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
