<?php

namespace PhpSchool\LearnYouPhp\Exercise;

use Faker\Generator;
use GuzzleHttp\Psr7\Request;
use PhpSchool\PhpWorkshop\Check\ComposerCheck;
use PhpSchool\PhpWorkshop\Exercise\AbstractExercise;
use PhpSchool\PhpWorkshop\Exercise\CgiExercise;
use PhpSchool\PhpWorkshop\Exercise\ExerciseInterface;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\ExerciseCheck\ComposerExerciseCheck;
use PhpSchool\PhpWorkshop\ExerciseDispatcher;
use PhpSchool\PhpWorkshop\Solution\DirectorySolution;
use PhpSchool\PhpWorkshop\Solution\SolutionInterface;
use Psr\Http\Message\RequestInterface;

class DependencyHeaven extends AbstractExercise implements
    ExerciseInterface,
    CgiExercise,
    ComposerExerciseCheck
{
    private Generator $faker;

    public function __construct(Generator $faker)
    {
        $this->faker = $faker;
    }

    public function getName(): string
    {
        return 'Dependency Heaven';
    }

    public function getDescription(): string
    {
        return 'An introduction to Composer dependency management';
    }

    public function getSolution(): SolutionInterface
    {
        return DirectorySolution::fromDirectory(__DIR__ . '/../../exercises/dependency-heaven/solution');
    }

    /**
     * @inheritdoc
     */
    public function getRequests(): array
    {
        $requests = [];

        for ($i = 0; $i < rand(2, 5); $i++) {
            $requests[] = $this->newApiRequest('/reverse');
            $requests[] = $this->newApiRequest('/snake');
            $requests[] = $this->newApiRequest('/titleize');
        }

        return $requests;
    }

    private function newApiRequest(string $endpoint): RequestInterface
    {
        $request = (new Request('POST', $endpoint))
            ->withHeader('Content-Type', 'application/x-www-form-urlencoded');

        $request->getBody()->write(
            http_build_query(['data' => $this->faker->sentence(rand(3, 6))])
        );

        return $request;
    }

    /**
     * @inheritdoc
     */
    public function getRequiredPackages(): array
    {
        return [
            'league/route',
            'laminas/laminas-diactoros',
            'laminas/laminas-httphandlerrunner',
            'symfony/string'
        ];
    }

    public function getType(): ExerciseType
    {
        return new ExerciseType(ExerciseType::CGI);
    }

    public function configure(ExerciseDispatcher $dispatcher): void
    {
        $dispatcher->requireCheck(ComposerCheck::class);
    }
}
