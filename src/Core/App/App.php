<?php
/**
 * Created by PhpStorm.
 * User: Botnikov Aleksandr
 * Date: 17.01.19
 * Time: 0:46
 */

namespace Core\App;


use Core\Kernel;

abstract class App
{

    public static function get($name)
    {
        return Kernel::boot()->get($name);
    }
}