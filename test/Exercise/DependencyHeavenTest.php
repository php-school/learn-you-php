<?php

namespace PhpSchool\LearnYouPhpTest\Exercise;

use Faker\Factory;
use Faker\Generator;
use PhpSchool\LearnYouPhp\Exercise\DependencyHeaven;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\Result\Success;
use PhpSchool\PhpWorkshop\Solution\SolutionInterface;
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

        $this->assertInstanceOf(SolutionInterface::class, $e->getSolution());
        $this->assertFileExists(realpath($e->getSolution()->getEntryPoint()));
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

        $endPoints = array_map(function (RequestInterface $request) {
            return $request->getUri()->getPath();
        }, $e->getRequests());

        $counts = array_count_values($endPoints);
        foreach (['/reverse', '/swapcase', '/titleize'] as $endPoint) {
            $this->assertTrue(isset($counts[$endPoint]));
            $this->assertGreaterThan(1, $counts[$endPoint]);
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

    /**
     * @dataProvider dependencyProvider
     *
     * @param $dependency
     * @param $solutionFile
     */
    public function testCheckReturnsFailureIfDependencyNotRequired($dependency, $solutionFile)
    {
        $e      = new DependencyHeaven($this->faker);
        $result = $e->check($solutionFile);

        $this->assertInstanceOf(Failure::class, $result);
        $this->assertSame('Dependency Heaven', $result->getCheckName());
        $this->assertSame(sprintf('Lockfile doesn\'t include "%s" at any version', $dependency), $result->getReason());
    }

    public function dependencyProvider()
    {
        return [
            ['klein/klein',           __DIR__ . '/../res/dependency-heaven/no-klein/solution.php'],
            ['danielstjules/stringy', __DIR__ . '/../res/dependency-heaven/no-stringy/solution.php']
        ];
    }

    public function testCheckReturnsSuccessIfCorrectLockfile()
    {
        $e      = new DependencyHeaven($this->faker);
        $result = $e->check(__DIR__ . '/../res/dependency-heaven/good-solution/solution.php');

        $this->assertInstanceOf(Success::class, $result);
        $this->assertSame('Dependency Heaven', $result->getCheckName());
    }
}
