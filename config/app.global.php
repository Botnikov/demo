<?php


use Zend\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;

return [
    'abstract_factories' => [
        ReflectionBasedAbstractFactory::class,
    ],

    'factories' => [

    ],
    'invokables' => [

    ],
    'services' => [
        'config' => [
        ],
        'env' => 'dev',
        'locale' => 'en',
    ]
];