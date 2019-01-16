<?php
/**
 * Created by PhpStorm.
 * User: Botnikov Aleksandr
 * Date: 16.01.19
 * Time: 22:38
 */

use Psr\Container\ContainerInterface;

return [

    'factories' => [

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
        ]
    ]

];