<?php

namespace Application\Service\User;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;
use Zend\Crypt\Password\Bcrypt;
use Application\Service\User\UserManager;

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
     * User entity manager.
     * @var UserManager
     */
    protected $userManager;

    public function __construct(UserManager $userManager) {
        $this->userManager = $userManager;
    }

    public function setEmail(string $email) {
        $this->email = $email;
    }

    public function setPassword(string $password) {
        $this->password = $password;
    }

    public function authenticate() {
        $user = $this->userManager->findOneByEmail($this->email);

        if ($user == null) {
            return new Result(Result::FAILURE_IDENTITY_NOT_FOUND, null, ['Invalid credentials.']);
        }

        $bcrypt = new Bcrypt();
        $passwordHash = $user->getPassword();

        if ($bcrypt->verify($this->password, $passwordHash)) {
            return new Result(Result::SUCCESS, $user->getId(), ['Authenticated successfully.']);
        }

        return new Result(Result::FAILURE_CREDENTIAL_INVALID, null, ['Invalid credentials.']);
    }

}
