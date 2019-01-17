<?php
/**
 * Created by PhpStorm.
 * User: Botnikov Aleksandr
 * Date: 18.01.19
 * Time: 0:04
 */

namespace Services\Template\Twig;


use Core\Template\TemplateRenderInterface;

class Twig implements TemplateRenderInterface
{

    public function __construct()
    {

    }

    /**
     *
     * @param $name
     * @param array $params
     * @return string
     */
    public function render($name, array $params = []): string
    {
        // TODO: Implement render() method.
    }
}