<?php

namespace PhpSchool\LearnYouPhpTest\Exercise;

use Faker\Factory;
use Faker\Generator;
use PhpSchool\LearnYouPhp\Exercise\DependencyHeaven;
use PhpSchool\PhpWorkshop\Check\ComposerCheck;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\ExerciseDispatcher;
use PhpSchool\PhpWorkshop\Solution\SolutionInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

/**
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
class DependencyHeavenTest extends TestCase
{

    /**
     * @var Generator
     */
    private $faker;

    public function setUp(): void
    {
        $this->faker = Factory::create('Fr_fr');
    }

    public function testDependencyHeavenExercise(): void
    {
        $e = new DependencyHeaven($this->faker);
        $this->assertEquals('Dependency Heaven', $e->getName());
        $this->assertEquals('An introduction to Composer dependency management', $e->getDescription());
        $this->assertEquals(ExerciseType::CGI, $e->getType());

        $this->assertInstanceOf(SolutionInterface::class, $e->getSolution());
        $this->assertFileExists(realpath($e->getSolution()->getEntryPoint()));
        $this->assertFileExists(realpath($e->getProblem()));
        $this->assertNull($e->tearDown());
    }

    public function testGetRequiredPackages(): void
    {
        $this->assertSame(
            ['klein/klein', 'danielstjules/stringy'],
            (new DependencyHeaven($this->faker))->getRequiredPackages()
        );
    }

    public function testGetRequests(): void
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

    public function testGetRequestsReturnsMultipleRequestsForEachEndpoint(): void
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

    public function testConfigure(): void
    {
        $dispatcher = $this->getMockBuilder(ExerciseDispatcher::class)
            ->disableOriginalConstructor()
            ->getMock();

        $dispatcher
            ->expects($this->once())
            ->method('requireCheck')
            ->with(ComposerCheck::class);

        $e = new DependencyHeaven($this->faker);
        $e->configure($dispatcher);
    }
}
