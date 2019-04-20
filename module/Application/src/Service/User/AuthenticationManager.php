<?php

namespace Application\Service\User;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Result;
use Zend\Session\SessionManager;
use Application\Entity\User\User;
use Application\Service\User\UserManager;

class AuthenticationManager {

    /**
     * Authentication service.
     * @var AuthenticationService
     */
    protected $authenticationService;

    /**
     * Session manager.
     * @var SessionManager
     */
    protected $sessionManager;

    /**
     * User entity manager.
     * @var UserManager
     */
    protected $userManager;

    /**
     * User entity cache.
     * Valid until page refresh or logout.
     * @var User
     */
    protected $currentUser;

    public function __construct(AuthenticationService $authenticationService,
                                SessionManager $sessionManager,
                                UserManager $userManager) {
        $this->authenticationService = $authenticationService;
        $this->sessionManager = $sessionManager;
        $this->userManager = $userManager;
    }

    public function getCurrentUser(): ?User {
        if (!$this->currentUser && $this->authenticationService->hasIdentity()) {
            $this->currentUser = $this->userManager->findOneById($this->authenticationService->hasIdentity());
        }

        return $this->currentUser;
    }

    public function login(string $email,
                          string $password,
                          bool $rememberMe,
                          int $cookieLifetime = 60 * 60 * 24 * 30) {
        if ($this->authenticationService->getIdentity() != null) {
            throw new \Exception('Already logged in');
        }

        $authenticationAdapter = $this->authenticationService->getAdapter();
        $authenticationAdapter->setEmail($email);
        $authenticationAdapter->setPassword($password);
        $result = $this->authenticationService->authenticate();

        if ($result->getCode() == Result::SUCCESS && $rememberMe) {
            $this->currentUser = $this->userManager->findOneById($result->getIdentity());
            $this->sessionManager->rememberMe($cookieLifetime);
        }

        return $result;
    }

    public function logout() {
        if ($this->authenticationService->getIdentity() == null) {
            throw new \Exception('The user is not logged in');
        }

        $this->currentUser = null;
        $this->authenticationService->clearIdentity();
    }

}
