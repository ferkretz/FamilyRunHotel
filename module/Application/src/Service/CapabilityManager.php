<?php

namespace Application\Service;

use Zend\Authentication\AuthenticationService;
use Application\Entity\User;
use Application\Service\UserManager;

class CapabilityManager {

    /**
     * Entity manager.
     * @var UserManager
     */
    private $userManager;

    /**
     * Authentication service.
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * Contents of the 'capability_config' config key.
     * @var array
     */
    private $config;

    public function __construct(UserManager $userManager,
                                AuthenticationService $authenticationService,
                                $config) {
        $this->userManager = $userManager;
        $this->authenticationService = $authenticationService;
        $this->config = $config;
    }

    public function userCan(User $user,
                            $capability) {
        return in_array($capability, $this->config[$user->getRole()]);
    }

    public function currentUserCan($capability) {
        $identity = $this->authenticationService->getIdentity();
        if ($identity == NULL) {
            return false;
        }

        $user = $this->userManager->findByEmail($identity);
        if ($user == NULL) {
            throw new \Exception('There is no user with such identity');
        }

        return $this->userCan($user, $capability);
    }

}
