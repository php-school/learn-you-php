<?php

namespace PhpSchool\LearnYouPhp\Exercise;

use PhpSchool\PhpWorkshop\Event\CliExecuteEvent;
use PhpSchool\PhpWorkshop\Event\EventDispatcher;
use PhpSchool\PhpWorkshop\Exception\RuntimeException;
use PhpSchool\PhpWorkshop\Exercise\AbstractExercise;
use PhpSchool\PhpWorkshop\Exercise\CliExercise;
use PhpSchool\PhpWorkshop\Exercise\ExerciseInterface;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\Exercise\Scenario\CliScenario;
use PhpSchool\PhpWorkshop\Output\OutputInterface;
use PhpSchool\PhpWorkshop\Result\ComparisonFailure;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\Result\Success;
use Socket;

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

    public function defineListeners(EventDispatcher $eventDispatcher): void
    {
        $referencePort = $this->getRandomPort();
        $studentPort = $this->getRandomPort();

        $eventDispatcher->listen(
            'cli.verify.reference-execute.pre',
            function (CliExecuteEvent $event) use ($referencePort) {
                $event->appendArg('0.0.0.0');
                $event->appendArg((string) $referencePort);
                $event->getScenario()->exposePort($referencePort);
            }
        );
        $eventDispatcher->listen(
            ['cli.verify.student-execute.pre', 'cli.run.student-execute.pre'],
            function (CliExecuteEvent $event) use ($studentPort) {
                $event->appendArg('0.0.0.0');
                $event->appendArg((string) $studentPort);
                $event->getScenario()->exposePort($studentPort);
            }
        );

        $eventDispatcher->listen(
            'cli.verify.reference.executing',
            function (CliExecuteEvent $event) use ($referencePort) {
                //wait for server to boot
                sleep(1);

                $socket = $this->createSocket();
                @socket_connect($socket, '0.0.0.0', $referencePort);
                @socket_read($socket, 2048, PHP_NORMAL_READ);

                socket_close($socket);

                //wait for shutdown
                usleep(100000);
            }
        );

        $eventDispatcher->insertVerifier(
            'cli.verify.student.executing',
            function (CliExecuteEvent $event) use ($studentPort) {
                //wait for server to boot
                sleep(1);

                $socket = $this->createSocket();

                $result = @socket_connect($socket, '0.0.0.0', $studentPort);

                if (!$result) {
                    $error  =  "Client returns an error (number %d): Connection refused ";
                    $error .= "while trying to join tcp://0.0.0.0:%d.";

                    return Failure::fromNameAndReason($this->getName(), sprintf(
                        $error,
                        socket_last_error($socket),
                        $studentPort
                    ));
                }

                $out = (string) socket_read($socket, 2048, PHP_NORMAL_READ);

                socket_close($socket);

                //wait for shutdown
                usleep(100000);

                $date = new \DateTime();

                //match the current date but any seconds
                //since we can't mock time in PHP easily
                if (!preg_match(sprintf('/^%s:([0-5][0-9]|60)\n$/', $date->format('Y-m-d H:i')), $out)) {
                    return ComparisonFailure::fromNameAndValues($this->getName(), $date->format("Y-m-d H:i:s\n"), $out);
                }
                return new Success($this->getName());
            }
        );

        $eventDispatcher->listen('cli.run.student.executing', function (CliExecuteEvent $event) use ($studentPort) {
            /** @var OutputInterface $output */
            $output = $event->getParameter('output');

            //wait for server to boot
            sleep(1);

            $socket = $this->createSocket();
            try {
                $connectResult = @socket_connect($socket, '0.0.0.0', $studentPort);
            } catch (\ErrorException $e) {
                $output->write('Cannot connect');
                return;
            }

            if (false === $connectResult) {
                $output->write('Cannot connect');
                return;
            }
            $out = (string) socket_read($socket, 2048, PHP_NORMAL_READ);

            //wait for shutdown
            usleep(100000);

            $output->write($out);
        });
    }

    private function getRandomPort(): int
    {
        $sock = socket_create_listen(0);

        if ($sock === false) {
            throw new RuntimeException('Cannot create socket');
        }

        socket_getsockname($sock, $addr, $port);
        socket_close($sock);

        return $port;
    }

    public function getType(): ExerciseType
    {
        return new ExerciseType(ExerciseType::CLI);
    }

    public function defineTestScenario(): CliScenario
    {
        return (new CliScenario())
            ->withExecution();
    }

    private function createSocket(): Socket
    {
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

        if ($socket === false) {
            throw new RuntimeException('Cannot create socket');
        }

        socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, ["sec" => 5, "usec" => 0]);

        return $socket;
    }
}
