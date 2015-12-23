<?php

namespace PhpSchool\LearnYouPhpTest\Exercise;

use Faker\Factory;
use Faker\Generator;
use PhpSchool\LearnYouPhp\Exercise\DependencyHeaven;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\Result\Success;
use PHPUnit_Framework_TestCase;
use Psr\Http\Message\RequestInterface;

/**
 * Class DependencyHeavenTest
 * @package PhpSchool\LearnYouPhpTest\Exercise
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
class DependencyHeavenTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Generator
     */
    private $faker;

    public function setUp()
    {
        $this->faker = Factory::create('Fr_fr');
    }

    public function testDependencyHeavenExercise()
    {
        $e = new DependencyHeaven($this->faker);

        $this->assertEquals('Dependency Heaven', $e->getName());
        $this->assertEquals('An introduction to Composer dependency management', $e->getDescription());

        $this->assertFileExists(realpath($e->getSolution()));
        $this->assertFileExists(realpath($e->getProblem()));
        $this->assertNull($e->tearDown());
    }

    public function testGetRequests()
    {
        $e = new DependencyHeaven($this->faker);

        $requests  = $e->getRequests();

        foreach ($requests as $request) {
            $this->assertInstanceOf(RequestInterface::class, $request);
            $this->assertSame('POST', $request->getMethod());
            $this->assertSame(['application/x-www-form-urlencoded'], $request->getHeader('Content-Type'));
            $this->assertNotEmpty($request->getBody());
        }
    }

    public function testGetRequestsReturnsMultipleRequestsForEachEnpoint()
    {
        $e = new DependencyHeaven($this->faker);

        $requests  = $e->getRequests();
        $endpoints = ['/reverse' => 0, '/swapcase' => 0, '/titleize' => 0];

        foreach ($requests as $request) {
            $endpoint = $request->getUri()->getPath();
            if (array_key_exists($endpoint, $endpoints)) {
                $endpoints[$endpoint]++;
            }
        }

        foreach ($endpoints as $count) {
            $this->assertGreaterThan(1, $count);
        }
    }

    public function testCheckReturnsFailureIfNoComposerFile()
    {
        $e      = new DependencyHeaven($this->faker);
        $result = $e->check('invalid/solution');

        $this->assertInstanceOf(Failure::class, $result);
        $this->assertSame('Dependency Heaven', $result->getCheckName());
        $this->assertSame('No composer.json file found', $result->getReason());
    }

    public function testCheckReturnsFailureIfNoComposerLockFile()
    {
        $e      = new DependencyHeaven($this->faker);
        $result = $e->check(__DIR__ . '/../res/dependency-heaven/not-locked/solution.php');

        $this->assertInstanceOf(Failure::class, $result);
        $this->assertSame('Dependency Heaven', $result->getCheckName());
        $this->assertSame('No composer.lock file found', $result->getReason());
    }

    public function testCheckReturnsFailureIfKleinNotRequired()
    {
        $e      = new DependencyHeaven($this->faker);
        $result = $e->check(__DIR__ . '/../res/dependency-heaven/wrong-solution/solution.php');

        $this->assertInstanceOf(Failure::class, $result);
        $this->assertSame('Dependency Heaven', $result->getCheckName());
        $this->assertSame('Lockfile doesn\'t include "klein/klein" at any version', $result->getReason());
    }

    public function testCheckReturnsSuccessIfCorrectLockfile()
    {
        $e      = new DependencyHeaven($this->faker);
        $result = $e->check(__DIR__ . '/../res/dependency-heaven/good-solution/solution.php');

        $this->assertInstanceOf(Success::class, $result);
        $this->assertSame('Dependency Heaven', $result->getCheckName());
    }
}
