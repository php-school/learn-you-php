<?php

namespace PhpSchool\LearnYouPhpTest\Exercise;

use Faker\Factory;
use Faker\Generator;
use PDO;
use PhpSchool\LearnYouPhp\Exercise\DatabaseRead;
use PhpSchool\PhpWorkshop\Application;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\TestUtils\WorkshopExerciseTest;

class DatabaseReadTest extends WorkshopExerciseTest
{
    private Generator $faker;

    public function setUp(): void
    {
        $this->faker = Factory::create();
        parent::setUp();
    }

    public function getApplication(): Application
    {
        return require __DIR__ . '/../../app/bootstrap.php';
    }

    public function getExerciseClass(): string
    {
        return DatabaseRead::class;
    }

    public function testExerciseMeta(): void
    {
        $e = new DatabaseRead($this->faker);
        $this->assertEquals('Database Read', $e->getName());
        $this->assertEquals('Read an SQL databases contents', $e->getDescription());
        $this->assertEquals(ExerciseType::CLI, $e->getType());

        $this->assertFileExists(realpath($e->getProblem()));
    }

    public function testDatabaseExercise(): void
    {
        $e = new DatabaseRead($this->faker);
        $this->assertEquals('Database Read', $e->getName());
        $this->assertEquals('Read an SQL databases contents', $e->getDescription());
        $this->assertEquals(ExerciseType::CLI, $e->getType());

        $this->assertFileExists(realpath($e->getProblem()));
    }

    public function testSeedAddsRandomUsersToDatabaseAndStoresRandomIdAndName(): void
    {
        $db = new PDO('sqlite::memory:');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $e = new DatabaseRead($this->faker);

        $e->seed($db);

        $scenario = $e->defineTestScenario();
        $stmt = $db->query('SELECT * FROM users;');

        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->assertTrue(count($users) >= 5);
        $this->assertIsArray($users);
        $this->assertContains($scenario->getExecutions()[0]->get(0), array_column($users, 'name'));
    }

    public function testVerifyReturnsTrueIfRecordExistsWithNameUsingStoredId(): void
    {
        $db = new PDO('sqlite::memory:');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $e = new DatabaseRead($this->faker);

        $rp = new \ReflectionProperty(DatabaseRead::class, 'randomRecord');
        $rp->setAccessible(true);
        $rp->setValue($e, ['id' => 5]);

        $db
            ->exec('CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY, name TEXT, age INTEGER, gender TEXT)');
        $stmt = $db->prepare('INSERT INTO users (id, name, age, gender) VALUES (:id, :name, :age, :gender)');
        $stmt->execute([':id' => 5, ':name' => 'David Attenborough', ':age' => 50, ':gender' => 'Male']);

        $this->assertTrue($e->verify($db));
    }

    public function testWithNoCode(): void
    {
        $this->runExercise('solution-no-code.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'No code was found');
    }

    public function testWithIncorrectOutput(): void
    {
        $this->runExercise('solution-wrong-output.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertOutputWasIncorrect();
    }

    public function testFailureWhenNameIsNotUpdated(): void
    {
        $this->runExercise('solution-wrong-update.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'Database verification failed');
    }

    public function testWithCorrectSolution(): void
    {
        $this->runExercise('solution-correct.php');

        $this->assertVerifyWasSuccessful();
    }
}
