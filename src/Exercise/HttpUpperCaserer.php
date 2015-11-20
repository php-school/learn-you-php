<?php

namespace PhpSchool\LearnYouPhp\Exercise;

use Faker\Generator;
use PhpSchool\PhpWorkshop\Exercise\ExerciseInterface;
use PhpSchool\PhpWorkshop\ExerciseCheck\CgiOutputExerciseCheck;
use Psr\Http\Message\RequestInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;
use Zend\Diactoros\Request;

/**
 * Class HttpUpperCaserer
 * @package PhpSchool\LearnYouPhp\Exercise
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class HttpUpperCaserer implements ExerciseInterface, CgiOutputExerciseCheck
{
    
    /**
     * @var Generator
     */
    private $faker;

    /**
     * HttpUpperCaserer constructor.
     * @param Generator $faker
     */
    public function __construct(Generator $faker)
    {
        $this->faker = $faker;
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return 'HTTP Upper Caserer';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Uppercase and return the HTTP POST body content';
    }

    /**
     * @return string
     */
    public function getSolution()
    {
        return __DIR__ . '/../../res/solutions/http-upper-caserer/solution.php';
    }

    /**
     * @return string
     */
    public function getProblem()
    {
        return __DIR__ . '/../../res/problems/http-upper-caserer/problem.md';
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
        $request = new Request('http://www.uppercaser.com', "POST");
        $request->getBody()->write($this->faker->word);
        
        return [$request];
    }
}
