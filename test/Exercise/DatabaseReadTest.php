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
use PHPUnit_Framework_TestCase;

/**
 * Class DatabaseReadTest
 * @package PhpSchool\LearnYouPhpTest\Exercise
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class DatabaseReadTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Generator
     */
    private $faker;

    public function setUp()
    {
        $this->faker = Factory::create();
    }

    public function testDatabaseExercise()
    {
        $e = new DatabaseRead($this->faker);
        $this->assertEquals('Database Read', $e->getName());
        $this->assertEquals('Read an SQL databases contents', $e->getDescription());
        $this->assertEquals(ExerciseType::CLI, $e->getType());

        $this->assertInstanceOf(SolutionInterface::class, $e->getSolution());
        $this->assertFileExists(realpath($e->getProblem()));
        $this->assertNull($e->tearDown());
    }

    public function testSeedAddsRandomUsersToDatabaseAndStoresRandomIdAndName()
    {
        $db = new PDO('sqlite::memory:');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $e = new DatabaseRead($this->faker);
        
        $e->seed($db);

        $args = $e->getArgs();
        $stmt = $db->query('SELECT * FROM users;');
        
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->assertTrue(count($users) >= 5);
        $this->assertInternalType('array', $users);
        $this->assertTrue(in_array($args[0], array_column($users, 'name')));
    }

    public function testVerifyReturnsTrueIfRecordExistsWithNameUsingStoredId()
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

    public function testConfigure()
    {
        $dispatcher = $this->getMockBuilder(ExerciseDispatcher::class)
            ->disableOriginalConstructor()
            ->getMock();

        $dispatcher
            ->expects($this->once())
            ->method('requireListenableCheck')
            ->with(DatabaseCheck::class);

        $e = new DatabaseRead($this->faker);
        $e->configure($dispatcher);
    }
}
