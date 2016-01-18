<?php

namespace PhpSchool\LearnYouPhp\Exercise;

use PhpSchool\PhpWorkshop\Exercise\AbstractExercise;
use PhpSchool\PhpWorkshop\Exercise\ExerciseInterface;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\ExerciseCheck\CgiOutputExerciseCheck;
use PhpSchool\PhpWorkshop\ExerciseDispatcher;
use Psr\Http\Message\RequestInterface;
use Zend\Diactoros\Request;

/**
 * Class HttpJsonApi
 * @package PhpSchool\LearnYouPhp\Exercise
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class HttpJsonApi extends AbstractExercise implements ExerciseInterface
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

    /**
     * @return ExerciseType
     */
    public function getType()
    {
        return ExerciseType::CGI();
    }
}
