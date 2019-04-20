<?php

namespace Application\Service\User;

use Zend\Mvc\MvcEvent;
use Application\Exception\Common\IllegalAccessError;
use Application\Entity\User\User;
use Application\Service\User\AuthenticationManager;

class AccessManager {

    /**
     * MvcEvent.
     * @var MvcEvent
     */
    protected $event;

    /**
     * AuthenticationManager manager.
     * @var AuthenticationManager
     */
    protected $authenticationManager;

    /**
     * Access manager config.
     * @var array
     */
    protected $config;

    public function __construct(MvcEvent $event,
                                AuthenticationManager $authenticationManager,
                                array $config) {
        $this->event = $event;
        $this->authenticationManager = $authenticationManager;
        $this->config = $config;
    }

    public function getRoleList() {
        $roleList = [];

        foreach ($this->config['roles'] as $name => $content) {
            $roleList[$name] = $content['summary'];
        }

        return $roleList;
    }

    public function currentUserIsGuest(): bool {
        return $this->authenticationManager->getCurrentUser() == null;
    }

    public function userCan(?User $user,
                            string $capability): bool {
        if ($user) {
            return $this->inCapabilities($user->getRole(), $capability, 'any');
        }

        return $this->inCapabilities('guest', $capability, 'any');
    }

    public function userCanOwn(int $userId,
                               ?User $user,
                               string $capability): bool {
        if ($user) {
            if (($user->getId() == $userId) && $this->inCapabilities($user->getRole(), $capability, 'own')) {
                return true;
            }

            return $this->inCapabilities($user->getRole(), $capability, 'any');
        }

        return false;
    }

    public function currentUserCan(string $capability): bool {
        return $this->userCan($this->authenticationManager->getCurrentUser(), $capability);
    }

    public function currentUserCanOwn(int $userId,
                                      string $capability): bool {
        return $this->userCanOwn($userId, $this->authenticationManager->getCurrentUser(), $capability);
    }

    public function currentUserTry(string $capability,
                                   ?string $errorMessage = null) {
        if (!$this->currentUserCan($capability)) {
            throw new IllegalAccessError($errorMessage);
        }
    }

    public function currentUserTryOwn(int $userId,
                                      string $capability,
                                      ?string $errorMessage = null) {
        if (!$this->currentUserCanOwn($userId, $capability)) {
            throw new IllegalAccessError($errorMessage);
        }
    }

    public function currentUserRedirect(string $capability,
                                        ?string $errorMessage = null) {
        if (!$this->currentUserCan($capability)) {
            if ($this->currentUserIsGuest()) {
                $this->redirectToLogin();
            }
            throw new IllegalAccessError($errorMessage);
        }
    }

    public function currentUserRedirectOwn(int $userId,
                                           string $capability,
                                           ?string $errorMessage = null) {
        if (!$this->currentUserCanOwn($userId, $capability)) {
            if ($this->currentUserIsGuest()) {
                $this->redirectToLogin();
            }
            throw new IllegalAccessError($errorMessage);
        }
    }

    private function redirectToLogin() {
        $uri = $this->event->getApplication()->getRequest()->getUri();
        $this->event->getTarget()->redirect()->toRoute('index-login', [], ['query' => ['redirectUrl' => $uri->setUserInfo(null)->toString()]]);
    }

    private function inCapabilities(string $role,
                                    string $capability,
                                    string $type): bool {
        if (!isset($this->config['roles'][$role]['capabilities'][$type])) {
            return false;
        }

        $parts = explode('.', $capability);

        foreach ($this->config['roles'][$role]['capabilities'][$type] as $name => $actions) {
            if (count($parts) == 1) {
                if (!is_array($actions)) {
                    if ($actions == $parts[0]) {
                        return true;
                    }
                }
            } else {
                if (is_array($actions)) {
                    if ($name == $parts[1]) {
                        foreach ($actions as $action) {
                            if ($action == $parts[0]) {
                                return true;
                            }
                        }
                    }
                }
            }
        }

        return false;
    }

}
