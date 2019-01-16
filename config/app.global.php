<?php

use Bukashk0zzz\FilterBundle\Service\Filter;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Cache\ApcCache;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Validator\Validation;
use Zend\HttpHandlerRunner\Emitter\SapiStreamEmitter;
use Zend\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;

return [
    'abstract_factories' => [
        ReflectionBasedAbstractFactory::class,
    ],

    'factories' => [
        SapiStreamEmitter::class => ReflectionBasedAbstractFactory::class,

        Aura\Router\RouterContainer::class => ReflectionBasedAbstractFactory::class,

        Services\Http\Auth\AuthFactory::class => function(){
            return new Services\Http\Auth\AuthFactory($_COOKIE);
        },

        Aura\Auth\Auth::class => \Services\Http\Auth\Auth::class,

        Aura\Auth\Adapter\AdapterInterface::class => function(ContainerInterface $container, $requestedName){
            $hash = new Aura\Auth\Verifier\PasswordVerifier(PASSWORD_BCRYPT);
            $cols = array(
                'users.username',
                'users.bcryptpass',
                'users.uid',
                'users.user_email AS email',
                'users.user_display_name AS display_name'
            );
            $from = 'users';
            $where = '';

            /**@var Services\Http\Auth\AuthFactory $authFactory*/
            $authFactory = $container->get(Services\Http\Auth\AuthFactory::class);

            $db = $container->get(Zend\Db\Adapter\Adapter::class);

            $pdo_adapter = $authFactory->newCustomPdoAdapter($db->getDriver()->getConnection()->getResource(), $hash, $cols, $from, $where);

            return $pdo_adapter;
        },

        ServerRequestInterface::class => function (ContainerInterface $container, $requestedName) {
            return Zend\Diactoros\ServerRequestFactory::fromGlobals();
        },


        Zend\Db\Adapter\Adapter::class => function (ContainerInterface $container, $requestedName) {
            return new Zend\Db\Adapter\Adapter($container->get('config')['pdo']);
        },


        Symfony\Component\Translation\TranslatorInterface::class => function (ContainerInterface $container, $requestedName) {

            return new \Symfony\Component\Translation\Translator($container->get('locale'));
        },

        Symfony\Component\Validator\Validator\ValidatorInterface::class => function (ContainerInterface $container, $requestedName) {
            $builder = Validation::createValidatorBuilder();
            $builder->enableAnnotationMapping();
            $builder->setTranslator($container->get(Symfony\Component\Translation\TranslatorInterface::class));
            return $builder->getValidator();
        },

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


        Filter::class => function(ContainerInterface $container, $requestedName){
            return new Filter($container->get(CachedReader::class));
        }

    ],
    'invokables' => [

    ],
    'services' => [
        'config' => [
            'pdo' => [
                'driver' => 'Pdo_Mysql',
                'driver_options' => [
                    \PDO::ATTR_ERRMODE,
                    \PDO::ERRMODE_EXCEPTION,
                ],

                'database' => 'demo',
                'hostname' => 'localhost',
                'port' => 3306,

                'username' => 'admin',
                'password' => 'admin',
            ]
        ],
        'env' => 'dev',
        'locale' => 'en',
    ]
];