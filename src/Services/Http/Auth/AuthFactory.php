<?php
/**
 * Created by PhpStorm.
 * User: Botnikov Aleksandr
 * Date: 15.01.19
 * Time: 23:34
 */

namespace Services\Http\Auth;


use PDO;
use \Aura\Auth\Verifier\PasswordVerifier;

class AuthFactory extends \Aura\Auth\AuthFactory
{
    public function newLoginServiceWithUpdatePid(AdapterInterface $adapter = null)
    {
        return new LoginService(
            $this->fixAdapter($adapter),
            $this->session
        );
    }

    public function newCustomPdoAdapter(
        PDO $pdo,
        $verifier_spec,
        array $cols,
        $from,
        $where = null
    )
    {
        if (is_object($verifier_spec)) {
            $verifier = $verifier_spec;
        } else {
            $verifier = new PasswordVerifier($verifier_spec);
        }

        return new PdoAdapter(
            $pdo,
            $verifier,
            $cols,
            $from,
            $where
        );
    }
}