<?php
/**
 * Created by PhpStorm.
 * User: Botnikov Aleksandr
 * Date: 15.01.19
 * Time: 23:46
 */

namespace Services\Http\Auth\Service;



use Aura\Auth\Session\SessionInterface;
use Aura\Auth\Status;

class LoginService
{
    /**
     *
     * Adapter of Adapterinterface
     *
     * @var mixed
     *
     */
    protected $adapter;

    /**
     *
     * session
     *
     * @var SessionInterface
     *
     */
    protected $session;

    /**
     *
     * Constructor.
     *
     * @param AdapterInterface $adapter A credential-storage adapter.
     *
     * @param SessionInterface $session A session manager.
     *
     */
    public function __construct(
        AdapterInterface $adapter,
        SessionInterface $session
    ) {
        $this->adapter = $adapter;
        $this->session = $session;
    }

    /**
     *
     * Logs the user in via the credential adapter.
     *
     * @param \Aura\Auth\Auth  $auth The authentication tracking object.
     *
     * @param array $input The credential input.
     *
     *
     *
     */
    public function login(\Aura\Auth\Auth $auth, array $input)
    {
        list($name, $data) = $this->adapter->login($input);
        $this->forceLogin($auth, $name, $data);
    }

    /**
     *
     * Forces a successful login.
     *
     * @param \Aura\Auth\Auth $auth The authentication tracking object.
     *
     * @param string $name The authenticated user name.
     *
     * @param array $data Additional arbitrary user data.
     *
     * @param string $status The new authentication status.
     *
     * @return string|false The authentication status on success, or boolean
     * false on failure.
     *
     */
    public function forceLogin(
        \Aura\Auth\Auth $auth,
        $name,
        array $data = array(),
        $status = Status::VALID
    ) {
        $started = $this->session->resume() || $this->session->start();
        if (! $started) {
            return false;
        }

        if(!$this->adapter->updateToken(session_id(), $this->session->regenerateId())){
            return false;
        }

        $auth->set(
            $status,
            time(),
            time(),
            $name,
            $data
        );

        return $status;
    }

}