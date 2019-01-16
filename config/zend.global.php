<?php


use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

use Zend\HttpHandlerRunner\Emitter\SapiStreamEmitter;
use Zend\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;

return [

    'factories' => [
        SapiStreamEmitter::class => ReflectionBasedAbstractFactory::class,

        ServerRequestInterface::class => function (ContainerInterface $container, $requestedName) {
            return Zend\Diactoros\ServerRequestFactory::fromGlobals();
        },
        Zend\Db\Adapter\Adapter::class => function (ContainerInterface $container) {
            return new Zend\Db\Adapter\Adapter($container->get('config')['pdo']);
        },

    ]
];