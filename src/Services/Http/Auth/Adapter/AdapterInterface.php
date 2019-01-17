<?php
/**
 * Created by PhpStorm.
 * User: Botnikov Aleksandr
 * Date: 16.01.19
 * Time: 0:24
 */

namespace Services\Http\Auth\Adapter;


interface AdapterInterface extends \Aura\Auth\Adapter\AdapterInterface
{
    public function updateToken(string $currentPid, string $newPid);
}