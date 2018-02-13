<?php

namespace Application\Service\User;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;
use Zend\Crypt\Password\Bcrypt;
use Application\Service\User\UserEntityManager;

class AuthenticationAdapter implements AdapterInterface {

    /**
     * User email.
     * @var string
     */
    protected $email;

    /**
     * Password
     * @var string
     */
    protected $password;

    /**
     * Entity manager.
     * @var UserEntityManager
     */
    protected $userEntityManager;

    public function __construct(UserEntityManager $userEntityManager) {
        $this->userEntityManager = $userEntityManager;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function authenticate() {
        $user = $this->userEntityManager->findOneByEmail($this->email);

        if ($user == NULL) {
            return new Result(Result::FAILURE_IDENTITY_NOT_FOUND, NULL, ['Invalid credentials.']);
        }

        $bcrypt = new Bcrypt();
        $passwordHash = $user->getPassword();

        if ($bcrypt->verify($this->password, $passwordHash)) {
            return new Result(Result::SUCCESS, $this->email, ['Authenticated successfully.']);
        }

        return new Result(Result::FAILURE_CREDENTIAL_INVALID, NULL, ['Invalid credentials.']);
    }

}
