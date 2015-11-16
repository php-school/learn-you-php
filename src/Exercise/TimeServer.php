<?php

namespace PhpSchool\LearnYouPhp\Exercise;

use PhpSchool\PhpWorkshop\Exercise\ExerciseInterface;
use PhpSchool\PhpWorkshop\ExerciseCheck\CgiOutputExerciseCheck;
use PhpSchool\PhpWorkshop\ExerciseCheck\SelfCheck;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\Result\ResultInterface;
use PhpSchool\PhpWorkshop\Result\Success;
use Psr\Http\Message\RequestInterface;
use Symfony\Component\Process\Process;
use Zend\Diactoros\Request;

/**
 * Class TimeServer
 * @package PhpSchool\LearnYouPhp\Exercise
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class TimeServer implements
    ExerciseInterface,
    SelfCheck
{
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
     * @return string
     */
    public function getSolution()
    {
        return __DIR__ . '/../../res/solutions/time-server/solution.php';
    }

    /**
     * @return string
     */
    public function getProblem()
    {
        return __DIR__ . '/../../res/problems/time-server/problem.md';
    }

    /**
     * @return null
     */
    public function tearDown()
    {
    }

    /**
     * @param string $fileName
     * @return ResultInterface
     */
    public function check($fileName)
    {
        $address    = '127.0.0.1';
        $port       = $this->getRandomPort();

        $cmd        = sprintf('%s %s %s', PHP_BINARY, $fileName, implode(' ', [$address, $port]));
        $process    = new Process($cmd, dirname($fileName));
        $process->start();
        
        //wait for server to boot
        sleep(1);
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        
        if (false === $socket) {
            return Failure::fromNameAndReason($this->getName(), 'Failed to create socket');
        }
        
        $result = @socket_connect($socket, $address, $port);
        
        if (false === $result) {
            return Failure::fromNameAndReason(
                $this->getName(),
                sprintf(
                    'Could not connect to %s:%s Reason: "%s"',
                    $address,
                    $port,
                    socket_strerror(socket_last_error($socket))
                )
            );
        }
        $out = socket_read($socket, 2048);
        //wait for shutdown
        sleep(1);
        
        if ($process->isRunning()) {
            $process->stop();
        }
        
        if ($process->isSuccessful()) {
            $date = new \DateTime;
            
            //match the current date but any seconds
            //since we can't mock time in PHP easily
            if (!preg_match(sprintf('/^%s:([0-5][0-9]|60)\n$/', $date->format('Y-m-d H:i')), $out)) {
                return Failure::fromNameAndReason($this->getName(), sprintf('Date is wrong. Got: "%s"', $out));
            }
            return new Success($this->getName());
        }

        return new Failure($this->getName(), $process->getErrorOutput());
    }

    /**
     * @return int
     */
    private function getRandomPort()
    {
        return 1024 + floor((mt_rand() / mt_getrandmax()) * 64511);
    }
}
