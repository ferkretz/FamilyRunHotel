<?php

namespace Application\Service;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;
use Zend\Crypt\Password\Bcrypt;
use Application\Service\UserManager;

class AuthenticationAdapter implements AdapterInterface {

    /**
     * User email.
     * @var string
     */
    private $email;

    /**
     * Password
     * @var string
     */
    private $password;

    /**
     * Entity manager.
     * @var UserManager
     */
    private $userManager;

    public function __construct(UserManager $userManager) {
        $this->userManager = $userManager;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function authenticate() {
        $user = $this->userManager->findByEmail($this->email);

        if ($user == null) {
            return new Result(
                    Result::FAILURE_IDENTITY_NOT_FOUND, null, ['Invalid credentials.']);
        }

        $bcrypt = new Bcrypt();
        $passwordHash = $user->getPassword();

        if ($bcrypt->verify($this->password, $passwordHash)) {
            return new Result(
                    Result::SUCCESS, $this->email, ['Authenticated successfully.']);
        }

        return new Result(
                Result::FAILURE_CREDENTIAL_INVALID, null, ['Invalid credentials.']);
    }

}
