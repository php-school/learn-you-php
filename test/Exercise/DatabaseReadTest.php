<?php

namespace PhpSchool\LearnYouPhpTest\Exercise;

use Faker\Factory;
use Faker\Generator;
use PDO;
use PhpSchool\LearnYouPhp\Exercise\DatabaseRead;
use PhpSchool\PhpWorkshop\Check\DatabaseCheck;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\ExerciseDispatcher;
use PhpSchool\PhpWorkshop\Solution\SolutionInterface;
use PHPUnit\Framework\TestCase;

class DatabaseReadTest extends TestCase
{
    /**
     * @var Generator
     */
    private $faker;

    public function setUp(): void
    {
        $this->faker = Factory::create();
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

        $args = $e->getArgs()[0];
        $stmt = $db->query('SELECT * FROM users;');

        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->assertTrue(count($users) >= 5);
        $this->assertIsArray($users);
        $this->assertContains($args[0], array_column($users, 'name'));
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

    public function testConfigure(): void
    {
        $dispatcher = $this->getMockBuilder(ExerciseDispatcher::class)
            ->disableOriginalConstructor()
            ->getMock();

        $dispatcher
            ->expects($this->once())
            ->method('requireCheck')
            ->with(DatabaseCheck::class);

        $e = new DatabaseRead($this->faker);
        $e->configure($dispatcher);
    }
}
