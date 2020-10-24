<?php

namespace PhpSchool\LearnYouPhpTest\Exercise;

use PhpSchool\LearnYouPhp\Exercise\HttpJsonApi;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\Solution\SolutionInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class HttpJsonApiTest extends TestCase
{
    public function testHttpJsonApiExercise()
    {
        $e = new HttpJsonApi();
        $this->assertEquals('HTTP JSON API', $e->getName());
        $this->assertEquals('HTTP JSON API - Servers JSON when it receives a GET request', $e->getDescription());
        $this->assertEquals(ExerciseType::CGI, $e->getType());

        $requests = $e->getRequests();
        $request1 = $requests[0];
        $request2 = $requests[1];

        $this->assertInstanceOf(RequestInterface::class, $request1);
        $this->assertInstanceOf(RequestInterface::class, $request2);

        $this->assertSame('GET', $request1->getMethod());
        $this->assertSame('GET', $request2->getMethod());
        $this->assertSame('www.time.com', $request1->getUri()->getHost());
        $this->assertSame('www.time.com', $request2->getUri()->getHost());
        $this->assertSame('/api/parsetime', $request1->getUri()->getPath());
        $this->assertSame('/api/unixtime', $request2->getUri()->getPath());

        $this->assertInstanceOf(SolutionInterface::class, $e->getSolution());
        $this->assertFileExists(realpath($e->getProblem()));
        $this->assertNull($e->tearDown());
    }
}
