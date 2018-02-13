<?php

namespace Application\Service\User;

use Zend\Authentication\AuthenticationService;
use Application\Entity\User\UserEntity;
use Application\Service\User\UserEntityManager;

class CurrentUserEntityManager {

    /**
     * AuthenticationService.
     * @var AuthenticationService
     */
    protected $authenticationService;

    /**
     * User manager.
     * @var UserEntityManager
     */
    protected $userEntityManager;

    /**
     * Current UserEntity cache.
     * @var UserEntity
     */
    protected $currentUserEntity;

    public function __construct(AuthenticationService $authenticationService,
                                UserEntityManager $userEntityManager) {
        $this->authenticationService = $authenticationService;
        $this->userEntityManager = $userEntityManager;
        $this->currentUserEntity = NULL;
    }

    public function get() {
        if ($this->authenticationService->hasIdentity()) {
            if ($this->currentUserEntity == NULL) {
                $this->currentUserEntity = $this->userEntityManager->findOneByEmail($this->authenticationService->getIdentity());
            }
            return $this->currentUserEntity;
        }

        return NULL;
    }

    public function clearCache() {
        $this->currentUserEntity = NULL;
    }

    public function update() {
        $this->userEntityManager->update();
    }

}
