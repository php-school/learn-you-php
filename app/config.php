<?php

use function DI\factory;
use function DI\object;
use Interop\Container\ContainerInterface;
use PhpSchool\LearnYouPhp\Exercise\ArrayWeGo;
use PhpSchool\LearnYouPhp\Exercise\BabySteps;
use PhpSchool\LearnYouPhp\Exercise\ExceptionalCoding;
use PhpSchool\LearnYouPhp\Exercise\FilteredLs;
use PhpSchool\LearnYouPhp\Exercise\HelloWorld;
use PhpSchool\LearnYouPhp\Exercise\HttpJsonApi;
use PhpSchool\LearnYouPhp\Exercise\HttpUpperCaserer;
use PhpSchool\LearnYouPhp\Exercise\MyFirstIo;
use Symfony\Component\Filesystem\Filesystem;
use Faker\Factory as FakerFactory;

return [
    //Exercises
    BabySteps::class            => object(BabySteps::class),
    HelloWorld::class           => object(HelloWorld::class),
    HttpJsonApi::class          => object(HttpJsonApi::class),
    MyFirstIo::class    => factory(function (ContainerInterface $c) {
        return new MyFirstIo($c->get(Filesystem::class), FakerFactory::create());
    }),
    FilteredLs::class   => factory(function (ContainerInterface $c) {
        return new FilteredLs($c->get(Filesystem::class));
    }),
    ArrayWeGo::class    => factory(function (ContainerInterface $c) {
        return new ArrayWeGo($c->get(Filesystem::class), FakerFactory::create());
    }),
    ExceptionalCoding::class => factory(function (ContainerInterface $c) {
        return new ExceptionalCoding($c->get(Filesystem::class), FakerFactory::create());
    }),
    TimeServer::class   => factory(function (ContainerInterface $c) {
        return new TimeServer(new TcpSocketFactory);
    }),
    HttpUpperCaserer::class => factory(function (ContainerInterface $c) {
        return new HttpUpperCaserer(FakerFactory::create());
    }),
];
