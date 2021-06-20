<?php

use Psr\Container\ContainerInterface;
use PhpParser\Parser;
use PhpSchool\LearnYouPhp\Exercise\ArrayWeGo;
use PhpSchool\LearnYouPhp\Exercise\BabySteps;
use PhpSchool\LearnYouPhp\Exercise\ConcernedAboutSeparation;
use PhpSchool\LearnYouPhp\Exercise\DatabaseRead;
use PhpSchool\LearnYouPhp\Exercise\ExceptionalCoding;
use PhpSchool\LearnYouPhp\Exercise\FilteredLs;
use PhpSchool\LearnYouPhp\Exercise\HelloWorld;
use PhpSchool\LearnYouPhp\Exercise\HttpJsonApi;
use PhpSchool\LearnYouPhp\Exercise\MyFirstIo;
use PhpSchool\LearnYouPhp\Exercise\TimeServer;
use PhpSchool\LearnYouPhp\Exercise\DependencyHeaven;
use PhpSchool\LearnYouPhp\TcpSocketFactory;
use PhpSchool\PhpWorkshop\Event\Event;
use Symfony\Component\Filesystem\Filesystem;
use Faker\Factory as FakerFactory;

use function DI\create;
use function DI\factory;

return [
    'basePath' => __DIR__ . '/../',

    //Exercises
    BabySteps::class    => create(BabySteps::class),
    HelloWorld::class   => create(HelloWorld::class),
    HttpJsonApi::class  => create(HttpJsonApi::class),
    MyFirstIo::class    => factory(function (ContainerInterface $c) {
        return new MyFirstIo($c->get(Filesystem::class), FakerFactory::create());
    }),
    FilteredLs::class   => factory(function (ContainerInterface $c) {
        return new FilteredLs($c->get(Filesystem::class));
    }),
    ConcernedAboutSeparation::class   => factory(function (ContainerInterface $c) {
        return new ConcernedAboutSeparation(
            $c->get(Filesystem::class),
            $c->get(Parser::class)
        );
    }),
    ArrayWeGo::class    => factory(function (ContainerInterface $c) {
        return new ArrayWeGo($c->get(Filesystem::class), FakerFactory::create());
    }),
    ExceptionalCoding::class => factory(function (ContainerInterface $c) {
        return new ExceptionalCoding($c->get(Filesystem::class), FakerFactory::create());
    }),
    DatabaseRead::class => factory(function (ContainerInterface $c) {
        return new DatabaseRead(FakerFactory::create());
    }),
    TimeServer::class   => factory(function (ContainerInterface $c) {
        return new TimeServer(new TcpSocketFactory);
    }),
    DependencyHeaven::class  => factory(function (ContainerInterface $c) {
        return new DependencyHeaven(FakerFactory::create('fr_FR'));
    }),

    'eventListeners' => [
        'create-solution-for-first-exercise' => [
            'exercise.selected.hello-world' => [
                DI\value(function () {
                    if (!file_exists(getcwd() . '/hello-world.php')) {
                        touch(getcwd() . '/hello-world.php');
                    }
                })
            ]
        ]
    ]
];
