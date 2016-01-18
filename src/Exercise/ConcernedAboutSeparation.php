<?php

namespace PhpSchool\LearnYouPhp\Exercise;

use Faker\Generator;
use PhpParser\Parser;
use PhpSchool\PhpWorkshop\Exercise\AbstractExercise;
use PhpSchool\PhpWorkshop\Exercise\CliExercise;
use PhpSchool\PhpWorkshop\Exercise\ExerciseInterface;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\Exercise\TemporaryDirectoryTrait;
use PhpSchool\PhpWorkshop\ExerciseCheck\SelfCheck;
use PhpSchool\PhpWorkshop\ExerciseCheck\StdOutExerciseCheck;
use PhpSchool\PhpWorkshop\ExerciseDispatcher;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\Result\ResultInterface;
use PhpSchool\PhpWorkshop\Result\Success;
use PhpSchool\PhpWorkshop\Solution\DirectorySolution;
use PhpSchool\PhpWorkshop\Solution\SolutionInterface;
use Symfony\Component\Filesystem\Filesystem;
use PhpParser\Node\Expr\Include_;

/**
 * Class ConcernedAboutSeparation
 * @package PhpSchool\LearnYouPhp\Exercise
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class ConcernedAboutSeparation extends AbstractExercise implements ExerciseInterface, CliExercise, SelfCheck
{
    use TemporaryDirectoryTrait;

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

    /**
     * @param Filesystem $filesystem
     * @param Generator $faker
     * @param Parser $parser
     */
    public function __construct(Filesystem $filesystem, Generator $faker, Parser $parser)
    {
        $this->filesystem = $filesystem;
        $this->faker = $faker;
        $this->parser = $parser;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Concerned about Separation?';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Separate code and utilise files and classes';
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
     * @return SolutionInterface
     */
    public function getSolution()
    {
        return DirectorySolution::fromDirectory(__DIR__ . '/../../exercises/concerned-about-separation/solution');
    }

    /**
     * @return null
     */
    public function tearDown()
    {
        $this->filesystem->remove($this->getTemporaryPath());
    }

    /**
     * @param string $fileName
     * @return ResultInterface
     */
    public function check($fileName)
    {
        $statements = $this->parser->parse(file_get_contents($fileName));

        $include = null;
        foreach ($statements as $statement) {
            if ($statement instanceof Include_) {
                $include = $statement;
                break;
            }
        }

        if (null === $include) {
            return Failure::fromNameAndReason($this->getName(), 'No require statement found');
        }

        return new Success($this->getName());
    }

    /**
     * @return ExerciseType
     */
    public function getType()
    {
        return ExerciseType::CLI();
    }
}
