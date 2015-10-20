<?php

use function DI\factory;
use function DI\object;
use Interop\Container\ContainerInterface;
use PhpSchool\LearnYouPhp\Exercise\BabySteps;
use PhpSchool\LearnYouPhp\Exercise\FilteredLs;
use PhpSchool\LearnYouPhp\Exercise\HelloWorld;
use PhpSchool\LearnYouPhp\Exercise\MyFirstIo;
use Symfony\Component\Filesystem\Filesystem;
use Faker\Factory as FakerFactory;

return [
    //Exercises
    BabySteps::class    => object(BabySteps::class),
    HelloWorld::class   => object(HelloWorld::class),
    MyFirstIo::class    => factory(function (ContainerInterface $c) {
        return new MyFirstIo($c->get(Filesystem::class), FakerFactory::create());
    }),
    FilteredLs::class   => factory(function (ContainerInterface $c) {
        return new FilteredLs($c->get(Filesystem::class));
    }),
];