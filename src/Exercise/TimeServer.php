<?php

namespace PhpSchool\LearnYouPhp\Exercise;

use PhpSchool\PhpWorkshop\Event\CliExecuteEvent;
use PhpSchool\PhpWorkshop\Exercise\AbstractExercise;
use PhpSchool\PhpWorkshop\Exercise\CliExercise;
use PhpSchool\PhpWorkshop\Exercise\ExerciseInterface;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\ExerciseDispatcher;
use PhpSchool\PhpWorkshop\Output\OutputInterface;
use PhpSchool\PhpWorkshop\Result\ComparisonFailure;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\Result\Success;

class TimeServer extends AbstractExercise implements ExerciseInterface, CliExercise
{
    public function getName(): string
    {
        return 'Time Server';
    }

    public function getDescription(): string
    {
        return 'Build a Time Server!';
    }

    public function configure(ExerciseDispatcher $exerciseDispatcher): void
    {
        $eventDispatcher = $exerciseDispatcher->getEventDispatcher();

        $appendArgsListener = function (CliExecuteEvent $event) {
            $event->appendArg('127.0.0.1');
            $event->appendArg($this->getRandomPort());
        };

        $eventDispatcher->listen('cli.verify.reference-execute.pre', $appendArgsListener);
        $eventDispatcher->listen('cli.verify.student-execute.pre', $appendArgsListener);
        $eventDispatcher->listen('cli.run.student-execute.pre', $appendArgsListener);

        $eventDispatcher->listen('cli.verify.reference.executing', function (CliExecuteEvent $event) {
            $args   = $event->getArgs()->getArrayCopy();

            //wait for server to boot
            usleep(100000);

            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            socket_connect($socket, $args[0], $args[1]);
            socket_read($socket, 2048, PHP_NORMAL_READ);

            //wait for shutdown
            usleep(100000);
        });

        $eventDispatcher->insertVerifier('cli.verify.student.executing', function (CliExecuteEvent $event) {
            $args   = $event->getArgs()->getArrayCopy();

            //wait for server to boot
            usleep(100000);

            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            $connectResult = @socket_connect($socket, $args[0], $args[1]);

            if (!$connectResult) {
                return Failure::fromNameAndReason($this->getName(), sprintf(
                    "Client returns an error (number %d): Connection refused while trying to join tcp://127.0.0.1:%d.",
                    socket_last_error($socket),
                    $args[1]
                ));
            }

            $out = socket_read($socket, 2048, PHP_NORMAL_READ);

            //wait for shutdown
            usleep(100000);

            $date = new \DateTime();

            //match the current date but any seconds
            //since we can't mock time in PHP easily
            if (!preg_match(sprintf('/^%s:([0-5][0-9]|60)\n$/', $date->format('Y-m-d H:i')), $out)) {
                return ComparisonFailure::fromNameAndValues($this->getName(), $date->format("Y-m-d H:i:s\n"), $out);
            }
            return new Success($this->getName());
        });

        $eventDispatcher->listen('cli.run.student.executing', function (CliExecuteEvent $event) {
            /** @var OutputInterface $output */
            $output = $event->getParameter('output');
            $args   = $event->getArgs()->getArrayCopy();

            //wait for server to boot
            usleep(100000);

            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            socket_connect($socket, $args[0], $args[1]);
            $out = socket_read($socket, 2048, PHP_NORMAL_READ);

            //wait for shutdown
            usleep(100000);

            $output->write($out);
        });
    }

    private function getRandomPort(): string
    {
        return (string) mt_rand(1025, 65535);
    }

    public function getType(): ExerciseType
    {
        return new ExerciseType(ExerciseType::CLI);
    }

    /**
     * @inheritdoc
     */
    public function getArgs(): array
    {
        return [];
    }
}
