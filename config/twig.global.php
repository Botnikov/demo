<?php
/**
 * Created by PhpStorm.
 * User: Botnikov Aleksandr
 * Date: 16.01.19
 * Time: 22:34
 */
return [
    'services' => [
        'twig' => [
            'extention_render_file' => '.twig.html',
            'path_templates' => 'Template/Twig',
            'enviroment' => [
                'debug' => false,
                'charset' => 'UTF-8',
                'base_template_class' => 'Twig_Template',
                'strict_variables' => false,
                'autoescape' => 'html',
                'cache' => false,
                'auto_reload' => null,
                'optimizations' => -1,
            ],

        ]
    ]
];