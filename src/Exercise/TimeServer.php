<?php

namespace PhpSchool\LearnYouPhp\Exercise;

use PhpSchool\PhpWorkshop\Event\CliExecuteEvent;
use PhpSchool\PhpWorkshop\Event\EventDispatcher;
use PhpSchool\PhpWorkshop\Exercise\AbstractExercise;
use PhpSchool\PhpWorkshop\Exercise\CliExercise;
use PhpSchool\PhpWorkshop\Exercise\ExerciseInterface;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\Exercise\Scenario\CliScenario;
use PhpSchool\PhpWorkshop\Output\OutputInterface;
use PhpSchool\PhpWorkshop\Process\FreePortPicker;
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

    public function defineListeners(EventDispatcher $eventDispatcher): void
    {
        $eventDispatcher->listen(
            'cli.verify.reference.executing',
            function (CliExecuteEvent $event) {
                $this->readTime($event);
            }
        );

        $eventDispatcher->insertVerifier(
            'cli.verify.student.executing',
            function (CliExecuteEvent $event) {
                $out = $this->readTime($event);

                if ($out === null) {
                    return Failure::fromNameAndReason($this->getName(), 'Cannot connect to your server');
                }

                $date = new \DateTime();

                //match the current date but any seconds
                //since we can't mock time in PHP easily
                if (!preg_match(sprintf('/^%s:([0-5][0-9]|60)\n$/', $date->format('Y-m-d H:i')), $out)) {
                    return ComparisonFailure::fromNameAndValues($this->getName(), $date->format("Y-m-d H:i:s\n"), $out);
                }
                return new Success($this->getName());
            }
        );

        $eventDispatcher->listen('cli.run.student.executing', function (CliExecuteEvent $event) {
            /** @var OutputInterface $output */
            $output = $event->getParameter('output');

            $output->write($this->readTime($event) ?? 'Cannot connect');
        });
    }

    public function getType(): ExerciseType
    {
        return new ExerciseType(ExerciseType::CLI);
    }

    public function defineTestScenario(): CliScenario
    {
        //free on this machine, and in a sandbox container every port is
        $port = (new FreePortPicker())->pick();

        return (new CliScenario())
            ->exposePort($port)
            ->withExecution(['0.0.0.0', (string) $port]);
    }

    /**
     * Connects to the server and reads the line it sends, or null when it can not connect.
     */
    private function readTime(CliExecuteEvent $event): ?string
    {
        //wait for server to boot
        sleep(1);

        $address = $event->getExecution()->getAddress($event->getScenario()->getExposedPorts()[0]);
        $client = @stream_socket_client('tcp://' . $address, $errorCode, $errorMessage, 5);
        if ($client === false) {
            return null;
        }

        stream_set_timeout($client, 5);
        $out = (string) fgets($client);
        fclose($client);

        //wait for shutdown
        usleep(100000);

        return $out;
    }
}
