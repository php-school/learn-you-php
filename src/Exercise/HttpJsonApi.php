<?php

namespace PhpSchool\LearnYouPhp\Exercise;

use GuzzleHttp\Psr7\Request;
use PhpSchool\PhpWorkshop\Exercise\AbstractExercise;
use PhpSchool\PhpWorkshop\Exercise\CgiExercise;
use PhpSchool\PhpWorkshop\Exercise\ExerciseInterface;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use Psr\Http\Message\RequestInterface;

class HttpJsonApi extends AbstractExercise implements ExerciseInterface, CgiExercise
{
    public function getName(): string
    {
        return 'HTTP JSON API';
    }

    public function getDescription(): string
    {
        return 'HTTP JSON API - Servers JSON when it receives a GET request';
    }

    /**
     * @inheritdoc
     */
    public function getRequests(): array
    {
        $url = 'http://www.time.com/api/%s?iso=%s';
        return [
            (new Request('GET', sprintf($url, 'parsetime', urlencode((new \DateTime())->format(DATE_ISO8601))))),
            (new Request('GET', sprintf($url, 'unixtime', urlencode((new \DateTime())->format(DATE_ISO8601)))))
        ];
    }

    public function getType(): ExerciseType
    {
        return new ExerciseType(ExerciseType::CGI);
    }
}
