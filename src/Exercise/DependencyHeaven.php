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
class DependencyHeaven extends AbstractExercise implements ExerciseInterface, CgiOutputExerciseCheck, SelfCheck
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
     * @return Request[]
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
     * @param $endpoint
     * @return Request
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
     * @param string $fileName
     * @return ResultInterface
     */
    public function check($fileName)
    {
        $composerFile = new JsonFile(sprintf('%s/composer.json', dirname($fileName)));
        $lockFile     = new JsonFile(sprintf('%s/composer.lock', dirname($fileName)));

        if (!$composerFile->exists()) {
            return new Failure($this->getName(), 'No composer.json file found');
        }

        if (!$lockFile->exists()) {
            return new Failure($this->getName(), 'No composer.lock file found');
        }

        $locker = new Locker(
            new NullIO,
            $lockFile,
            new RepositoryManager(new NullIO, new Config),
            new InstallationManager,
            file_get_contents($composerFile->getPath())
        );

        if (!$locker->getLockedRepository()->findPackages('klein/klein')) {
            return new Failure($this->getName(), 'Lockfile doesn\'t include "klein/klein" at any version');
        }

        return new Success($this->getName());
    }
}
