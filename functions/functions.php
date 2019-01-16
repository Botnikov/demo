<?php
/**
 * Created by PhpStorm.
 * User: Botnikov Aleksandr
 * Date: 10.01.19
 * Time: 22:52
 */
if (!function_exists('dd')) {
    function dd($data, $continue = false)
    {
        echo '<pre>';
        var_dump($data);
        echo '<pre>';

        if (!$continue) {
            exit;
        }
    }
}