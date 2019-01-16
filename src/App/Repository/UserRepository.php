<?php
/**
 * Created by PhpStorm.
 * User: Botnikov Aleksandr
 * Date: 14.01.19
 * Time: 23:55
 */

namespace App\Repository;

use App\Model\User;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;


class UserRepository
{
    /**
     * @var Adapter
     */
    private $db;

    public function __construct(Adapter $db)
    {
        $this->db = $db;
    }

    /**
     * @param User $user
     * @return \Zend\Db\Adapter\Driver\ResultInterface
     */
    public function registerNewUser(User $user)
    {
        $sql = new Sql($this->db);

        $data = array_diff($user->mapModelToArray($user), array(''));

        $insert = $sql->insert('users')->values($data);

        $statement = $sql->prepareStatementForSqlObject($insert);

        return $statement->execute();
    }

    public function checkUserFields(User $user)
    {

        $statement = $this->db->createStatement("SELECT * FROM users WHERE `username` = '{$user->getUserName()}' OR  `user_email` ='{$user->getUserEmail()}'");
        $statement->prepare();
        return $statement->execute();
    }
}