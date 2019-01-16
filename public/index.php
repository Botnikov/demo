<?php
/**
 * Created by PhpStorm.
 * User: Botnikov Aleksandr
 * Date: 10.01.19
 * Time: 22:44
 */

use Core\Kernel;

chdir(dirname(__DIR__));
require_once "vendor/autoload.php";

try {
    $kernel = Kernel::boot();

    $routerContainer = $kernel->get(Aura\Router\RouterContainer::class);

    $routerContainer->getMap()->get('home', '/', ['service' => App\Http\Controller\IndexController::class, 'action' => 'index']);
    $routerContainer->getMap()->post('register', '/register', ['service' => App\Http\Controller\Auth\Register::class, 'action' => 'store']);
    $routerContainer->getMap()->post('login', '/login', ['service' => App\Http\Controller\Auth\Login::class, 'action' => 'check']);

    $kernel->handleRequest();

} catch (\Throwable $th) {
    dd($th);
    exit('Service Unavailable');
}

