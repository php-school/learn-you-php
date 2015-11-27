<?php

namespace PhpSchool\LearnYouPhp\Exercise;

use Faker\Generator;
use PDO;
use PhpSchool\PhpWorkshop\CodeInsertion as CI;
use PhpSchool\PhpWorkshop\Exercise\AbstractExercise;
use PhpSchool\PhpWorkshop\Exercise\ExerciseInterface;
use PhpSchool\PhpWorkshop\Exercise\SubmissionPatchable;
use PhpSchool\PhpWorkshop\Exercise\TemporaryDirectoryTrait;
use PhpSchool\PhpWorkshop\ExerciseCheck\SelfCheck;
use PhpSchool\PhpWorkshop\ExerciseCheck\StdOutExerciseCheck;
use PhpSchool\PhpWorkshop\Patch;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\Result\ResultInterface;
use PhpSchool\PhpWorkshop\Result\Success;
use PhpSchool\PhpWorkshop\SubmissionPatch;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class DatabaseRead
 * @package PhpSchool\LearnYouPhp\Exercise
 * @author Michael Woodawrd <mikeymike.mw@gmail.com>
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class DatabaseRead extends AbstractExercise implements
    ExerciseInterface,
    StdOutExerciseCheck,
    SelfCheck,
    SubmissionPatchable
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
     * @var PDO
     */
    private $db;

    /**
     * @var int
     */
    private $randomId;

    /**
     * @param Generator $faker
     */
    public function __construct(Filesystem $filesystem, Generator $faker)
    {
        $this->filesystem = $filesystem;
        $this->faker      = $faker;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Database Read';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Read an SQL databases contents';
    }

    /**
     * @return array
     */
    public function getArgs()
    {
        $this->filesystem->mkdir($this->getTemporaryPath());

        $dbPath   = sprintf('sqlite:%s/db.sqlite', $this->getTemporaryPath());
        $this->db = new PDO($dbPath);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $names          = $this->populateDb();
        $this->randomId = array_rand($names);

        return [$dbPath, $names[$this->randomId]];
    }

    /**
     * @return null
     */
    public function tearDown()
    {
        $this->filesystem->remove($this->getTemporaryPath());
    }

    /**
     * Populate database with fake data
     */
    private function populateDb()
    {
        $this->db
            ->exec('CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY, name TEXT, age INTEGER, gender TEXT)');
        $stmt = $this->db->prepare('INSERT into users (name, age, gender) VALUES (:name, :age, :gender)');

        $names = [];
        foreach (range(0, rand(5, 15)) as $id) {
            $name   = $this->faker->name;
            $age    = rand(18, 90);
            $gender = rand(0, 100) % 2 ? 'male' : 'female';

            $stmt->execute([':name' => $name, ':age' => $age, ':gender' => $gender]);
            $id = $this->db->lastInsertId();
            $names[$id] = $name;
        }
        return $names;
    }

    /**
     * @param string $fileName
     * @return ResultInterface
     */
    public function check($fileName)
    {
        $sql = 'SELECT name FROM users WHERE id = :id';
        $stmt = $this->db->prepare($sql);

        $stmt->execute([':id' => $this->randomId]);
        $result = $stmt->fetchColumn();
        
        if ($result !== 'David Attenborough') {
            return Failure::fromNameAndReason('Update Database', 'The requested row was not updated');
        }
        
        return new Success('Update Database');
    }

    /**
     * @return Patch
     */
    public function getPatch()
    {
        return (new Patch)
            //hack to keep an un-modified version of the db for solution and submission checking
            //otherwise the time the db is passed to submission, it is modified.    
            ->withInsertion(new CI(CI::TYPE_BEFORE, '$___db = substr($argv[1], 7); copy($___db, "originalDb");'))
            ->withInsertion(new CI(CI::TYPE_AFTER, 'rename("originalDb", $___db);'));
    }
}
