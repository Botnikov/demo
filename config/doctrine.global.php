<?php

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Cache\ApcCache;
use Psr\Container\ContainerInterface;


return [

    'factories' => [
        AnnotationReader::class => function (ContainerInterface $container, $requestedName) {
            return new AnnotationReader();
        },
        CachedReader::class => function (ContainerInterface $container, $requestedName) {
            return new CachedReader(
                $container->get(AnnotationReader::class),
                new ApcCache(),
                $debug = true
            );
        },
    ],

];