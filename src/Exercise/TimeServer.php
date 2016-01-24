<?php

namespace PhpSchool\LearnYouPhp\Exercise;

use Hoa\Core\Exception\Exception;
use PhpSchool\LearnYouPhp\TcpSocketFactory;
use PhpSchool\PhpWorkshop\Event\CliExecuteEvent;
use PhpSchool\PhpWorkshop\Exercise\AbstractExercise;
use PhpSchool\PhpWorkshop\Exercise\CliExercise;
use PhpSchool\PhpWorkshop\Exercise\ExerciseInterface;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\ExerciseDispatcher;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\Result\StdOutFailure;
use PhpSchool\PhpWorkshop\Result\Success;
use Psr\Http\Message\RequestInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;
use Zend\Diactoros\Request;

/**
 * Class TimeServer
 * @package PhpSchool\LearnYouPhp\Exercise
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class TimeServer extends AbstractExercise implements ExerciseInterface, CliExercise
{

    /**
     * @var TcpSocketFactory
     */
    private $socketFactory;

    /**
     * TimeServer constructor.
     * @param TcpSocketFactory $socketFactory
     */
    public function __construct(TcpSocketFactory $socketFactory)
    {
        $this->socketFactory = $socketFactory;
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return 'Time Server';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Build a Time Server!';
    }

    /**
     * @param ExerciseDispatcher $exerciseDispatcher
     */
    public function configure(ExerciseDispatcher $exerciseDispatcher)
    {
        $eventDispatcher = $exerciseDispatcher->getEventDispatcher();

        $eventDispatcher->listen('cli.verify.solution-execute.pre', function (CliExecuteEvent $event) {
            $event->appendArg('127.0.0.1');
            $event->appendArg($this->getRandomPort());
        });

        $eventDispatcher->listen('cli.verify.user-execute.pre', function (CliExecuteEvent $event) {
            $event->appendArg('127.0.0.1');
            $event->appendArg($this->getRandomPort());
        });

        $eventDispatcher->listen('cli.verify.solution.executing', function (CliExecuteEvent $event) {
            $args   = $event->getArgs()->getArrayCopy();
            $client = $this->socketFactory->createClient(...$args);

            //wait for server to boot
            usleep(100000);

            $client->connect();
            $client->readAll();

            //wait for shutdown
            usleep(100000);
        });

        $eventDispatcher->insertVerifier('cli.verify.user.executing', function (CliExecuteEvent $event) {
            $args   = $event->getArgs()->getArrayCopy();
            $client = $this->socketFactory->createClient(...$args);

            //wait for server to boot
            usleep(100000);

            try {
                $client->connect();
            } catch (Exception $e) {
                return Failure::fromNameAndReason($this->getName(), $e->getMessage());
            }

            $out = $client->readAll();

            //wait for shutdown
            usleep(100000);

            $date = new \DateTime;

            //match the current date but any seconds
            //since we can't mock time in PHP easily
            if (!preg_match(sprintf('/^%s:([0-5][0-9]|60)\n$/', $date->format('Y-m-d H:i')), $out)) {
                return new StdOutFailure($this->getName(), $date->format("Y-m-d H:i:s\n"), $out);
            }
            return new Success($this->getName());
        });
    }

    /**
     * @return string
     */
    private function getRandomPort()
    {
        return (string) mt_rand(1025, 65535);
    }

    /**
     * @return ExerciseType
     */
    public function getType()
    {
        return ExerciseType::CLI();
    }

    /**
     * @return string[]
     */
    public function getArgs()
    {
        return [];
    }
}
