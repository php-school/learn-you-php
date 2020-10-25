<?php

namespace PhpSchool\LearnYouPhp\Exercise;

use Faker\Generator;
use PDO;
use PhpSchool\PhpWorkshop\Check\DatabaseCheck;
use PhpSchool\PhpWorkshop\Check\ListenableCheckInterface;
use PhpSchool\PhpWorkshop\Event\Event;
use PhpSchool\PhpWorkshop\Event\EventDispatcher;
use PhpSchool\PhpWorkshop\Exercise\AbstractExercise;
use PhpSchool\PhpWorkshop\Exercise\CliExercise;
use PhpSchool\PhpWorkshop\Exercise\ExerciseInterface;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\ExerciseCheck\DatabaseExerciseCheck;
use PhpSchool\PhpWorkshop\ExerciseDispatcher;
use PhpSchool\PhpWorkshop\SubmissionPatch;
use Symfony\Component\Filesystem\Filesystem;

class DatabaseRead extends AbstractExercise implements ExerciseInterface, DatabaseExerciseCheck, CliExercise
{

    /**
     * @var Generator
     */
    private $faker;

    /**
     * @var array
     */
    private $randomRecord;

    /**
     * @param Generator $faker
     */
    public function __construct(Generator $faker)
    {
        $this->faker = $faker;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Database Read';
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Read an SQL databases contents';
    }

    /**
     * @return array
     */
    public function getArgs(): array
    {
        return [$this->randomRecord['name']];
    }

    /**
     * @param PDO $db
     * @return void
     */
    public function seed(PDO $db): void
    {
        $db
            ->exec('CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY, name TEXT, age INTEGER, gender TEXT)');
        $stmt = $db->prepare('INSERT INTO users (name, age, gender) VALUES (:name, :age, :gender)');

        $names = [];
        for ($i = 0; $i < $this->faker->numberBetween(5, 15); $i++) {
            $name   = $this->faker->name;
            $age    = rand(18, 90);
            $gender = rand(0, 100) % 2 ? 'male' : 'female';

            $stmt->execute([':name' => $name, ':age' => $age, ':gender' => $gender]);
            $id = $db->lastInsertId();
            $names[$id] = $name;
        }

        $randomId = array_rand($names);
        $this->randomRecord = ['id' => $randomId, 'name' => $names[$randomId]];
    }

    /**
     * @param PDO $db
     * @return bool
     */
    public function verify(PDO $db): bool
    {
        $sql = 'SELECT name FROM users WHERE id = :id';
        $stmt = $db->prepare($sql);

        $stmt->execute([':id' => $this->randomRecord['id']]);
        $result = $stmt->fetchColumn();

        return $result === 'David Attenborough';
    }

    /**
     * @return ExerciseType
     */
    public function getType(): ExerciseType
    {
        return ExerciseType::CLI();
    }

    /**
     * @param ExerciseDispatcher $dispatcher
     */
    public function configure(ExerciseDispatcher $dispatcher): void
    {
        $dispatcher->requireCheck(DatabaseCheck::class);
    }
}
