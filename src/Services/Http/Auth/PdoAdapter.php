<?php
/**
 * Created by PhpStorm.
 * User: Botnikov Aleksandr
 * Date: 15.01.19
 * Time: 23:51
 */

namespace Services\Http\Auth;


use Aura\Auth\Status;

class PdoAdapter extends \Aura\Auth\Adapter\PdoAdapter
{
    public function updateToken(string $currentPid, string $newPid)
    {
        try {
            $statment = $this->pdo->prepare("UPDATE `users` SET uid=:newPid WHERE  uid=:curreentPid");
            $statment->bindParam(':newPid', md5($newPid));
            $statment->bindParam(':curreentPid', md5($currentPid));
            $result = $statment->execute();
            if ($result) {
                return true;
            }
            return false;
        } catch (\Exception $ex) {
            //ToDo Добавить запись в логи
            return false;
        }
    }

    public function resume(\Aura\Auth\Auth $auth)
    {

        $cols = $this->buildSelectCols();
        $from = $this->buildSelectFrom();

        $where = "uid = :uid";
        if ($this->where) {
            $where .= " AND ({$this->where})";
        }
        $data = $this->fetchRows("SELECT {$cols} FROM {$from} WHERE {$where}", [':uid' => md5(session_id())]);

        if (count($data) == 1) {

            $name = $data['username'];
            unset($data['username']);
            unset($data['password']);
            $auth->set(
                Status::VALID,
                time(),
                time(),
                $name,
                $data
            );
        }

    }
}