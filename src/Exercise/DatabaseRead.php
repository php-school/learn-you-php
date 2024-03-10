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
use Symfony\Component\Filesystem\Filesystem;

class DatabaseRead extends AbstractExercise implements ExerciseInterface, DatabaseExerciseCheck, CliExercise
{
    private Generator $faker;

    /**
     * @var array{id: int, name: string}
     */
    private array $randomRecord;

    public function __construct(Generator $faker)
    {
        $this->faker = $faker;
    }

    public function getName(): string
    {
        return 'Database Read';
    }

    public function getDescription(): string
    {
        return 'Read an SQL databases contents';
    }

    /**
     * @inheritdoc
     */
    public function getArgs(): array
    {
        return [[$this->randomRecord['name']]];
    }

    public function seed(PDO $db): void
    {
        $db
            ->exec('CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY, name TEXT, age INTEGER, gender TEXT)');
        $stmt = $db->prepare('INSERT INTO users (name, age, gender) VALUES (:name, :age, :gender)');

        if ($stmt == false) {
            return;
        }

        $names = [];
        for ($i = 0; $i < $this->faker->numberBetween(5, 15); $i++) {
            $name   = $this->faker->name();
            $age    = rand(18, 90);
            $gender = rand(0, 100) % 2 ? 'male' : 'female';

            $stmt->execute([':name' => $name, ':age' => $age, ':gender' => $gender]);
            $names[(int) $db->lastInsertId()] = $name;
        }

        $randomId = (int) array_rand($names);
        $this->randomRecord = ['id' => $randomId, 'name' => (string) $names[$randomId]];
    }

    public function verify(PDO $db): bool
    {
        $sql = 'SELECT name FROM users WHERE id = :id';
        $stmt = $db->prepare($sql);

        if ($stmt == false) {
            return false;
        }

        $stmt->execute([':id' => $this->randomRecord['id']]);
        $result = $stmt->fetchColumn();

        return $result === 'David Attenborough';
    }

    public function getType(): ExerciseType
    {
        return new ExerciseType(ExerciseType::CLI);
    }

    public function configure(ExerciseDispatcher $dispatcher): void
    {
        $dispatcher->requireCheck(DatabaseCheck::class);
    }
}
