<?php

namespace PhpSchool\LearnYouPhp\Exercise;

use PhpSchool\PhpWorkshop\Exercise\AbstractExercise;
use PhpSchool\PhpWorkshop\Exercise\CliExercise;
use PhpSchool\PhpWorkshop\Exercise\ExerciseInterface;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\Exercise\TemporaryDirectoryTrait;
use PhpSchool\PhpWorkshop\ExerciseCheck\StdOutExerciseCheck;
use PhpSchool\PhpWorkshop\ExerciseDispatcher;
use Symfony\Component\Filesystem\Filesystem;

class FilteredLs extends AbstractExercise implements ExerciseInterface, CliExercise
{
    use TemporaryDirectoryTrait;

    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function getName(): string
    {
        return 'Filtered LS';
    }

    public function getDescription(): string
    {
        return 'Read files in a folder and filter by a given extension';
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

    public function tearDown(): void
    {
        $this->filesystem->remove($this->getTemporaryPath());
    }

    public function getType(): ExerciseType
    {
        return new ExerciseType(ExerciseType::CLI);
    }
}
