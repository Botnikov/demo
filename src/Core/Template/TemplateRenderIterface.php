<?php
/**
 * Created by PhpStorm.
 * User: Botnikov Aleksandr
 * Date: 16.01.19
 * Time: 23:45
 */

namespace Core\Template;


interface TemplateRenderIterface
{
    /**
     *
     * @param $name
     * @param array $params
     * @return string
     */
    public function render($name, array $params = []): string;
}