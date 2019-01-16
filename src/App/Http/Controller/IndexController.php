<?php
/**
 * Created by PhpStorm.
 * User: Botnikov Aleksandr
 * Date: 12.01.19
 * Time: 23:29
 */

namespace App\Http\Controller;


use App\Model\User;
use Core\App\AbstractController;
use Services\Http\Auth\Register;
use Zend\Db\Sql\Sql;
use Zend\Diactoros\Response\HtmlResponse;

class IndexController extends AbstractController
{


    public function index()
    {

        dd($this->request);

        $user = new User();
        $user->setUserName('Aleksandr');
        $user->setUserEmail('test@test.loc');

        $auth = new Register($this->db);
        $auth->register($user);

        $sql = new Sql($this->db);
        $select = $sql->select();
        $select->from('users');


        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();
        dd($this->db->getDriver()->getConnection()->getResource());

        return new HtmlResponse();
    }
}