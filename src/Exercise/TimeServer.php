<?php

namespace PhpSchool\LearnYouPhp\Exercise;

use Hoa\Core\Exception\Exception;
use PhpSchool\LearnYouPhp\TcpSocketFactory;
use PhpSchool\PhpWorkshop\Exercise\AbstractExercise;
use PhpSchool\PhpWorkshop\Exercise\CliExercise;
use PhpSchool\PhpWorkshop\Exercise\ExerciseInterface;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\ExerciseCheck\SelfCheck;
use PhpSchool\PhpWorkshop\ExerciseDispatcher;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\Result\ResultInterface;
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
class TimeServer extends AbstractExercise implements ExerciseInterface, CliExercise, SelfCheck
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
        usleep(100000);
        
        $client = $this->socketFactory->createClient($address, $port);
        
        try {
            $client->connect();
        } catch (Exception $e) {
            return Failure::fromNameAndReason($this->getName(), $e->getMessage());
        }
      
        $out = $client->readAll();
        
        //wait for shutdown
        usleep(100000);
        
        if ($process->isRunning()) {
            $process->stop();
        }
        
        if ($process->isSuccessful()) {
            $date = new \DateTime;
            
            //match the current date but any seconds
            //since we can't mock time in PHP easily
            if (!preg_match(sprintf('/^%s:([0-5][0-9]|60)\n$/', $date->format('Y-m-d H:i')), $out)) {
                return new StdOutFailure($this->getName(), $date->format("Y-m-d H:i:s\n"), $out);
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
        return mt_rand(1025, 65535);
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
        return ['127.0.0.1', $this->getRandomPort()];
    }
}
