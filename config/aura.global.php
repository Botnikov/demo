<?php

use Psr\Container\ContainerInterface;

use Zend\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;

return [

    'factories' => [
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
    ]
];