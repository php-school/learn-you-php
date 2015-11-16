<?php

namespace PhpSchool\LearnYouPhp\Exercise;

use PhpSchool\PhpWorkshop\Exercise\ExerciseInterface;
use PhpSchool\PhpWorkshop\ExerciseCheck\CgiOutputExerciseCheck;
use Psr\Http\Message\RequestInterface;
use Zend\Diactoros\Request;

/**
 * Class HttpJsonApi
 * @package PhpSchool\LearnYouPhp\Exercise
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class HttpJsonApi implements
    ExerciseInterface,
    CgiOutputExerciseCheck
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'HTTP JSON API';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'HTTP JSON API - Servers JSON when it receives a GET request';
    }

    /**
     * @return string
     */
    public function getSolution()
    {
        return __DIR__ . '/../../res/solutions/http-json-api/solution.php';
    }

    /**
     * @return string
     */
    public function getProblem()
    {
        return __DIR__ . '/../../res/problems/http-json-api/problem.md';
    }

    /**
     * @return null
     */
    public function tearDown()
    {
    }
    
    /**
     * @return RequestInterface[]
     */
    public function getRequests()
    {
        $url = 'http://www.time.com/api/%s?iso=%s';
        return [
            (new Request(sprintf($url, 'parsetime', (new \DateTime)->format(DATE_ISO8601))))
                ->withMethod('GET'),
            (new Request(sprintf($url, 'unixtime', (new \DateTime)->format(DATE_ISO8601))))
                ->withMethod('GET'),
        ];
    }
}
