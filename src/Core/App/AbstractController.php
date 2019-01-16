<?php
/**
 * Created by PhpStorm.
 * User: Botnikov Aleksandr
 * Date: 13.01.19
 * Time: 20:56
 */

namespace Core\App;


use Aura\Auth\AuthFactory;
use Core\Kernel;
use Psr\Http\Message\ServerRequestInterface;
use Aura\Auth\Auth;
use Zend\Db\Adapter\Adapter;

abstract class AbstractController
{
    /**
     * @var ServerRequestInterface
     */
    protected $request;
    /**
     * @var Adapter
     */
    protected $db;
    /**
     * @var AuthFactory
     */
    protected $auth;
    /**
     * @var Kernel
     */
    protected $kernel;

    public function __construct(ServerRequestInterface $request, Adapter $db, Auth $auth, Kernel $kernel)
    {
        $this->request = $request;
        $this->db = $db;
        $this->auth = $auth;
        $this->kernel = $kernel;
    }
}