<?php
/**
 * Created by PhpStorm.
 * User: Botnikov Aleksandr
 * Date: 14.01.19
 * Time: 0:48
 */

namespace App\Http\Controller\Auth;


use App\Model\User;
use App\Repository\UserRepository;
use Aura\Auth\Verifier\PasswordVerifier;
use Bukashk0zzz\FilterBundle\Service\Filter;
use Core\App\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\Response\RedirectResponse;

class Register extends AbstractController
{
    public function index()
    {
        if (!$this->auth->isAnon()) {
            return new RedirectResponse('/');
        }
    }

    public function store()
    {
        $errors = [];

        if (!$this->auth->isAnon()) {
            return new RedirectResponse('/');
        }

        $user = new User();
        $user->mapArrayToModel($user, $this->request->getParsedBody());
        $user->setUserId(null);

        $filter = $this->kernel->getContainer()->get(Filter::class);
        $filter->filterEntity($user);

        if (!empty($password = $user->getBcryptpass())) {
            $user->setBcryptpass(password_hash($password, PASSWORD_BCRYPT));
        }

        $validator = $this->kernel->getContainer()->get(ValidatorInterface::class);
        $violations = $validator->validate($user);

        if (!count($violations)) {
            try {
                $repository = new UserRepository($this->db);
                $check = $repository->checkUserFields($user);
                if ($check->count()) {
                    $errors[] = "Username or email available";
                } else {
                    $result = $repository->registerNewUser($user);
                    if ($result->count()) {
                        $hash = new PasswordVerifier(PASSWORD_BCRYPT);
                        $cols = array(
                            'users.username', // "AS username" is added by the adapter
                            'users.bcryptpass', // "AS password" is added by the adapter
                            'users.uid',
                            'users.user_email AS email',
                            'users.user_display_name AS display_name'

                        );
                        $from = 'users';
                        $where = '';

                        $pdo_adapter = $this->auth->newPdoAdapter($this->db->getDriver()->getConnection()->getResource(), $hash, $cols, $from, $where);
                        $login_service = $this->auth->newLoginService($pdo_adapter);

                        $login_service->login($this->auth, array(
                            'username' => $this->request->getParsedBody()['username'],
                            'password' => $this->request->getParsedBody()['bcryptpass']
                        ));
                    }
                }

            } catch (\Exception $ex) {
                dd($ex->getMessage());
            }
        } else {
            $errors[] = "It is necessary to fill in the form data";
        }

        return new JsonResponse($errors);
    }
}