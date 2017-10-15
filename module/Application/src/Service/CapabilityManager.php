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
    protected $userManager;

    /**
     * Authentication service.
     * @var AuthenticationService
     */
    protected $authenticationService;

    /**
     * Contents of the 'role_config' config key.
     * @var array
     */
    protected $roleConfig;

    public function __construct(UserManager $userManager,
                                AuthenticationService $authenticationService,
                                $roleConfig) {
        $this->userManager = $userManager;
        $this->authenticationService = $authenticationService;
        $this->roleConfig = $roleConfig;
    }

    public function userCan(User $user,
                            $capability): bool {
        if (!isset($this->roleConfig[$user->getRole()]['capabilities'])) {
            return FALSE;
        }

        return in_array($capability, $this->roleConfig[$user->getRole()]['capabilities']);
    }

    public function currentUserCan($capability): bool {
        $identity = $this->authenticationService->getIdentity();
        if ($identity == NULL) {
            return FALSE;
        }

        $user = $this->userManager->findByEmail($identity);
        if ($user == NULL) {
            throw new \Exception('There is no user with such identity');
        }

        return $this->userCan($user, $capability);
    }

}
