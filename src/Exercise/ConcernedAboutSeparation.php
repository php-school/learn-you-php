<?php

namespace PhpSchool\LearnYouPhp\Exercise;

use PhpParser\Node\Stmt\Expression;
use PhpParser\Parser;
use PhpSchool\PhpWorkshop\Exercise\AbstractExercise;
use PhpSchool\PhpWorkshop\Exercise\CliExercise;
use PhpSchool\PhpWorkshop\Exercise\ExerciseInterface;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\Exercise\Scenario\CliScenario;
use PhpSchool\PhpWorkshop\ExerciseCheck\SelfCheck;
use PhpSchool\PhpWorkshop\ExerciseRunner\Context\ExecutionContext;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\Result\ResultInterface;
use PhpSchool\PhpWorkshop\Result\Success;
use PhpSchool\PhpWorkshop\Solution\DirectorySolution;
use PhpSchool\PhpWorkshop\Solution\SolutionInterface;
use PhpParser\Node\Expr\Include_;

class ConcernedAboutSeparation extends AbstractExercise implements ExerciseInterface, CliExercise, SelfCheck
{
    public function __construct(private Parser $parser)
    {
    }

    public function getName(): string
    {
        return 'Concerned about Separation?';
    }

    public function getDescription(): string
    {
        return 'Separate code and utilise files and classes';
    }

    public function defineTestScenario(): CliScenario
    {
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

        $ext = '';
        while ($ext === '') {
            $index = array_rand($files);
            $ext = pathinfo($files[$index], PATHINFO_EXTENSION);
        }

        $scenario = (new CliScenario())
            ->withExecution(['files', $ext]);

        array_walk($files, function (string $file) use ($scenario) {
            $scenario->withFile('files/' . $file, '');
        });

        return $scenario;
    }

    public function getSolution(): SolutionInterface
    {
        return DirectorySolution::fromDirectory(__DIR__ . '/../../exercises/concerned-about-separation/solution');
    }

    public function check(ExecutionContext $context): ResultInterface
    {
        $statements = $this->parser->parse((string) file_get_contents($context->getEntryPoint()));

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
