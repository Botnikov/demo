<?php
/**
 * Created by PhpStorm.
 * User: Botnikov Aleksandr
 * Date: 13.01.19
 * Time: 22:43
 */

namespace Services\Http\Auth;


use Psr\Container\ContainerInterface;

class Auth
{
    public function __invoke(ContainerInterface $container, $requestedName)
    {
        return $container->get(AuthFactory::class)->newInstance();
    }
}