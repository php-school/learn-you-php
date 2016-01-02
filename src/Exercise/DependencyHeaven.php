<?php

namespace PhpSchool\LearnYouPhp\Exercise;

use Composer\Config;
use Composer\Installer\InstallationManager;
use Composer\IO\NullIO;
use Composer\Json\JsonFile;
use Composer\Package\Locker;
use Composer\Repository\RepositoryManager;
use Faker\Generator;
use PhpSchool\PhpWorkshop\Exercise\AbstractExercise;
use PhpSchool\PhpWorkshop\Exercise\ExerciseInterface;
use PhpSchool\PhpWorkshop\ExerciseCheck\CgiOutputExerciseCheck;
use PhpSchool\PhpWorkshop\ExerciseCheck\ComposerExerciseCheck;
use PhpSchool\PhpWorkshop\ExerciseCheck\SelfCheck;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\Result\ResultInterface;
use PhpSchool\PhpWorkshop\Result\Success;
use Zend\Diactoros\Request;

/**
 * Class DependencyHeaven
 * @package PhpSchool\LearnYouPhp\Exercise
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
class DependencyHeaven extends AbstractExercise implements 
    ExerciseInterface,
    CgiOutputExerciseCheck,
    ComposerExerciseCheck
{
    /**
     * @var Generator
     */
    private $faker;

    /**
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
        return 'Dependency Heaven';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'An introduction to Composer dependency management';
    }

    /**
     * @return RequestInterface[]
     */
    public function getRequests()
    {
        $requests = [];

        for ($i = 0; $i < rand(2, 5); $i++) {
            $requests[] = $this->newApiRequest('/reverse');
            $requests[] = $this->newApiRequest('/swapcase');
            $requests[] = $this->newApiRequest('/titleize');
        }

        return $requests;
    }

    /**
     * @param string $endpoint
     * @return RequestInterface
     */
    private function newApiRequest($endpoint)
    {
        $request = (new Request($endpoint))
            ->withMethod('POST')
            ->withHeader('Content-Type', 'application/x-www-form-urlencoded');

        $request->getBody()->write(
            http_build_query(['data' => $this->faker->sentence(rand(3, 6))])
        );

        return $request;
    }

    /**
     * @return array
     */
    public function getRequiredPackages()
    {
        return [
            'klein/klein',
            'danielstjules/stringy'
        ];
    }
}
