<?php

namespace PhpSchool\LearnYouPhp\Exercise;

use PhpParser\Node\Stmt\Expression;
use PhpParser\Parser;
use PhpSchool\PhpWorkshop\Exercise\AbstractExercise;
use PhpSchool\PhpWorkshop\Exercise\CliExercise;
use PhpSchool\PhpWorkshop\Exercise\ExerciseInterface;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\Exercise\TemporaryDirectoryTrait;
use PhpSchool\PhpWorkshop\ExerciseCheck\SelfCheck;
use PhpSchool\PhpWorkshop\Input\Input;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\Result\ResultInterface;
use PhpSchool\PhpWorkshop\Result\Success;
use PhpSchool\PhpWorkshop\Solution\DirectorySolution;
use PhpSchool\PhpWorkshop\Solution\SolutionInterface;
use Symfony\Component\Filesystem\Filesystem;
use PhpParser\Node\Expr\Include_;

class ConcernedAboutSeparation extends AbstractExercise implements ExerciseInterface, CliExercise, SelfCheck
{
    use TemporaryDirectoryTrait;

    private Filesystem $filesystem;
    private Parser $parser;

    public function __construct(Filesystem $filesystem, Parser $parser)
    {
        $this->filesystem = $filesystem;
        $this->parser = $parser;
    }

    public function getName(): string
    {
        return 'Concerned about Separation?';
    }

    public function getDescription(): string
    {
        return 'Separate code and utilise files and classes';
    }

    /**
     * @inheritdoc
     */
    public function getArgs(): array
    {
        $folder = $this->getTemporaryPath();

        $files = [
            "learnyouphp.dat",
            "learnyouphp.txt",
            "learnyouphp.sql",
            "txt",
            "sql",
            "api.html",
            "html",
            "README.md",
            "CHANGELOG.md",
            "LICENCE.md",
            "md",
            "data.json",
            "json",
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

        return [[$folder, $ext]];
    }

    public function getSolution(): SolutionInterface
    {
        return DirectorySolution::fromDirectory(__DIR__ . '/../../exercises/concerned-about-separation/solution');
    }

    public function tearDown(): void
    {
        $this->filesystem->remove($this->getTemporaryPath());
    }

    public function check(Input $input): ResultInterface
    {
        $statements = $this->parser->parse((string) file_get_contents($input->getRequiredArgument('program')));

        if (null === $statements) {
            return Failure::fromNameAndReason($this->getName(), 'No code was found');
        }

        $include = null;
        foreach ($statements as $statement) {
            if ($statement instanceof Expression && $statement->expr instanceof Include_) {
                $include = $statement;
                break;
            }
        }

        if (null === $include) {
            return Failure::fromNameAndReason($this->getName(), 'No require statement found');
        }

        return new Success($this->getName());
    }

    public function getType(): ExerciseType
    {
        return new ExerciseType(ExerciseType::CLI);
    }
}
