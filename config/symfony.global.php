<?php

use Bukashk0zzz\FilterBundle\Service\Filter;
use Doctrine\Common\Annotations\CachedReader;
use Psr\Container\ContainerInterface;
use Symfony\Component\Validator\Validation;


return [

    'factories' => [

        Symfony\Component\Translation\TranslatorInterface::class => function (ContainerInterface $container, $requestedName) {

            return new \Symfony\Component\Translation\Translator($container->get('locale'));
        },

        Symfony\Component\Validator\Validator\ValidatorInterface::class => function (ContainerInterface $container, $requestedName) {
            $builder = Validation::createValidatorBuilder();
            $builder->enableAnnotationMapping();
            $builder->setTranslator($container->get(Symfony\Component\Translation\TranslatorInterface::class));
            return $builder->getValidator();
        },


        Filter::class => function (ContainerInterface $container, $requestedName) {
            return new Filter($container->get(CachedReader::class));
        }

    ]
];