<?php

namespace PhpSchool\LearnYouPhpTest\Exercise;

use Faker\Factory;
use Faker\Generator;
use PhpSchool\LearnYouPhp\Exercise\ArrayWeGo;
use PhpSchool\PhpWorkshop\Check\FunctionRequirementsCheck;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\ExerciseDispatcher;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

class ArrayWeGoTest extends TestCase
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
        $e = new ArrayWeGo($this->filesystem, $this->faker);
        $this->assertEquals('Array We Go!', $e->getName());
        $this->assertEquals('Filter an array of file paths and map to SplFile objects', $e->getDescription());
        $this->assertEquals(ExerciseType::CLI, $e->getType());

        $this->assertFileExists(realpath($e->getProblem()));
    }

    public function testGetArgsCreateAtLeastOneExistingFile(): void
    {
        $e = new ArrayWeGo($this->filesystem, $this->faker);
        $args = $e->getArgs()[0];

        $existingFiles = array_filter($args, 'file_exists');

        foreach ($existingFiles as $file) {
            $this->assertFileExists($file);
        }

        $this->assertGreaterThanOrEqual(1, count($existingFiles));
    }

    public function testGetArgsHasAtLeastOneNonExistingFile(): void
    {
        $e = new ArrayWeGo($this->filesystem, $this->faker);
        $args = $e->getArgs()[0];

        $nonExistingFiles = array_filter($args, function ($arg) {
            return !file_exists($arg);
        });

        foreach ($nonExistingFiles as $file) {
            $this->assertFileDoesNotExist($file);
        }

        $this->assertGreaterThanOrEqual(1, count($nonExistingFiles));
    }

    public function testTearDownRemovesFile(): void
    {
        $e = new ArrayWeGo($this->filesystem, $this->faker);
        $args = $e->getArgs()[0];

        $existingFiles = array_filter($args, 'file_exists');

        $this->assertFileExists($existingFiles[0]);

        $e->tearDown();

        $this->assertFileDoesNotExist($existingFiles[0]);
    }

    public function testFunctionRequirements(): void
    {
        $e = new ArrayWeGo($this->filesystem, $this->faker);
        $this->assertEquals(['array_shift', 'array_filter', 'array_map'], $e->getRequiredFunctions());
        $this->assertEquals(['basename'], $e->getBannedFunctions());
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

        $e = new ArrayWeGo($this->filesystem, $this->faker);
        $e->configure($dispatcher);
    }
}
