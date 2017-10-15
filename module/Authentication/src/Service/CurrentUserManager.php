<?php

namespace Authentication\Service;

use Zend\Authentication\AuthenticationService;
use Application\Entity\User;
use Application\Service\UserManager;

class CurrentUserManager {

    /**
     * AuthenticationService.
     * @var AuthenticationService
     */
    protected $authenticationService;

    /**
     * User manager.
     * @var UserManager
     */
    protected $userManager;

    /**
     * Current User.
     * @var User
     */
    protected $currentUser;

    public function __construct(AuthenticationService $authenticationService,
                                UserManager $userManager) {
        $this->authenticationService = $authenticationService;
        $this->userManager = $userManager;
        $this->currentUser = NULL;
    }

    public function get() {
        if ($this->authenticationService->hasIdentity()) {
            if ($this->currentUser == NULL) {
                $this->currentUser = $this->userManager->findByEmail($this->authenticationService->getIdentity());
            }
            return $this->currentUser;
        }

        return NULL;
    }

    public function clear() {
        $this->currentUser = NULL;
    }

    public function update() {
        $this->userManager->update();
    }

}
