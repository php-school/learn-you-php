<?php

namespace PhpSchool\LearnYouPhpTest\Exercise;

use PhpParser\Parser;
use PhpParser\ParserFactory;
use PhpSchool\LearnYouPhp\Exercise\ConcernedAboutSeparation;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\Input\Input;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\Result\Success;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

class ConcernedAboutSeparationTest extends TestCase
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var Parser
     */
    private $parser;

    public function setUp(): void
    {
        $this->filesystem = new Filesystem();
        $this->parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
    }

    public function testConcernedAboutSeparationExercise(): void
    {
        $e = new ConcernedAboutSeparation($this->filesystem, $this->parser);
        $this->assertEquals('Concerned about Separation?', $e->getName());
        $this->assertEquals('Separate code and utilise files and classes', $e->getDescription());
        $this->assertEquals(ExerciseType::CLI, $e->getType());

        $this->assertFileExists(realpath($e->getProblem()));
    }

    public function testGetArgsCreatesFilesAndReturnsRandomExt(): void
    {
        $e = new ConcernedAboutSeparation($this->filesystem, $this->parser);
        $args = $e->getArgs()[0];
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

        $this->assertContains($args[1], $extensions);
    }

    public function testTearDownRemovesFile(): void
    {
        $e = new ConcernedAboutSeparation($this->filesystem, $this->parser);
        $args = $e->getArgs()[0];
        $path = $args[0];
        $this->assertFileExists($path);

        $e->tearDown();

        $this->assertFileDoesNotExist($path);
    }

    public function testCheckReturnsFailureIfNoIncludeFoundInSolution(): void
    {
        $e = new ConcernedAboutSeparation($this->filesystem, $this->parser);
        $failure = $e->check(
            new Input('learnyouphp', ['program' => __DIR__ . '/../res/concerned-about-separation/no-include.php'])
        );

        $this->assertInstanceOf(Failure::class, $failure);
        $this->assertEquals('No require statement found', $failure->getReason());
        $this->assertEquals('Concerned about Separation?', $failure->getCheckName());
    }

    public function testCheckReturnsSuccessIfIncludeFound(): void
    {
        $e = new ConcernedAboutSeparation($this->filesystem, $this->parser);
        $success = $e->check(
            new Input('learnyouphp', ['program' => __DIR__ . '/../res/concerned-about-separation/include.php'])
        );

        $this->assertInstanceOf(Success::class, $success);
        $this->assertEquals('Concerned about Separation?', $success->getCheckName());
    }
}
