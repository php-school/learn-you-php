<?php

namespace PhpSchool\LearnYouPhp\Exercise;

use Faker\Generator;
use PhpSchool\PhpWorkshop\Exercise\ExerciseInterface;
use PhpSchool\PhpWorkshop\ExerciseCheck\FunctionRequirementsExerciseCheck;
use PhpSchool\PhpWorkshop\ExerciseCheck\StdOutExerciseCheck;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class DatabaseRead
 * @package PhpSchool\LearnYouPhp\Exercise
 * @author Michael Woodawrd <mikeymike.mw@gmail.com>
 */
class DatabaseRead implements
    ExerciseInterface,
    StdOutExerciseCheck
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var Generator
     */
    private $faker;

    /**
     * @var \PDO
     */
    private $db;

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
     * @return string
     */
    public function getSolution()
    {
        return __DIR__ . '/../../res/solutions/database-read/solution.php';
    }

    /**
     * @return string
     */
    public function getProblem()
    {
        return __DIR__ . '/../../res/problems/database-read/problem.md';
    }

    /**
     * @return array
     */
    public function getArgs()
    {
        $this->filesystem->mkdir($this->getTemporaryPath());

        $dbPath   = sprintf('sqlite:%s/db.sqlite', $this->getTemporaryPath());
        $this->db = new \PDO($dbPath);
        $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $this->populateDb();

        return [$dbPath];
    }

    /**
     * @return null
     */
    public function tearDown()
    {
        $this->filesystem->remove($this->getTemporaryPath());
    }

    /**
     * @return string
     */
    private function getTemporaryPath()
    {
        return sprintf('%s/%s', realpath(sys_get_temp_dir()), str_replace('\\', '_', __CLASS__));
    }

    /**
     * Populate database with fake data
     */
    private function populateDb()
    {
        $this->db->exec(
            'CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY, name TEXT, age INTEGER, gender TEXT)'
        );

        $stmt   = $this->db->prepare('INSERT into users (name, age, gender) VALUES (:name, :age, :gender)');

        foreach (range(0, rand(5, 15)) as $id) {
            $name  = $this->faker->name;
            $age   = rand(18, 90);
            $gender = rand(0, 100) % 2 ? 'male' : 'female';

            $stmt->bindParam('name', $name, \PDO::PARAM_STR);
            $stmt->bindParam('age', $age, \PDO::PARAM_INT);
            $stmt->bindParam('gender', $gender, \PDO::PARAM_STR);

            $stmt->execute();
        }

        $users = $this->db->query('SELECT * FROM users');

        $test = '';
        foreach ($users as $user) {
            $test .= sprintf('Name: %s, Age: %s, Gender: %s', $user['name'], $user['age'], $user['gender']);
            $test .= "\n";
        }

    }
}
