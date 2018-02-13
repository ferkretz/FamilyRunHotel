<?php

namespace Application\Service\User;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Result;
use Zend\Session\SessionManager;
use Application\Service\User\CurrentUserEntityManager;

class AuthenticationManager {

    // Constants returned by the access filter.
    const ALREADY_LOGGED_IN = 0;
    const ACCESS_GRANTED = 1;
    const AUTHENTICATION_REQUIRED = 2;
    const ACCESS_DENIED = 3;

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
     * Authentication service.
     * @var CurrentUserEntityManager;
     */
    protected $currentUserEntityManager;

    /**
     * Contents of the 'access_filter' config key.
     * @var array
     */
    protected $config;

    public function __construct(AuthenticationService $authenticationService,
                                SessionManager $sessionManager,
                                CurrentUserEntityManager $currentUserEntityManager,
                                $config) {
        $this->authenticationService = $authenticationService;
        $this->sessionManager = $sessionManager;
        $this->currentUserEntityManager = $currentUserEntityManager;
        $this->config = $config;
    }

    public function login($email,
                          $password,
                          $rememberMe) {
        if ($this->authenticationService->getIdentity() != NULL) {
            throw new \Exception('Already logged in');
        }

        $authenticationAdapter = $this->authenticationService->getAdapter();
        $authenticationAdapter->setEmail($email);
        $authenticationAdapter->setPassword($password);
        $result = $this->authenticationService->authenticate();

        if ($result->getCode() == Result::SUCCESS && $rememberMe) {
            $this->sessionManager->rememberMe(60 * 60 * 24 * 30);
        }

        return $result;
    }

    public function logout() {
        if ($this->authenticationService->getIdentity() == NULL) {
            throw new \Exception('The user is not logged in');
        }

        $this->authenticationService->clearIdentity();
    }

    public function filterAccessMenuItem($id) {
        if (isset($this->config['menuItems'])) {
            $items = $this->config['menuItems'];
            foreach ($items as $item) {
                if (in_array($id, $item['ids'])) {
                    if ($this->filterAccessItem($item) == self::ACCESS_GRANTED) {
                        return self::ACCESS_GRANTED;
                    }

                    return self::ACCESS_DENIED;
                }
            }
        }

        return self::ACCESS_DENIED;
    }

    public function filterAccess($controllerName,
                                 $actionName) {
        if (isset($this->config['controllers'][$controllerName])) {
            $items = $this->config['controllers'][$controllerName];
            foreach ($items as $item) {
                if (in_array($actionName, $item['actions'])) {
                    return $this->filterAccessItem($item);
                }
            }
        }

        return self::ACCESS_DENIED;
    }

    private function filterAccessItem($item,
                                      $userId = 0) {
        // no allow, no access
        if (!isset($item['allow'])) {
            return self::ACCESS_DENIED;
        }

        // allow for anyone
        if (in_array('*', $item['allow'])) {
            return self::ACCESS_GRANTED;
        }

        // allow only for guests
        if (in_array('!guests', $item['allow'])) {
            if ($this->authenticationService->hasIdentity()) {
                return self::ALREADY_LOGGED_IN;
            }
            return self::ACCESS_GRANTED;
        }

        if ($this->authenticationService->hasIdentity()) {
            $currentUserEntity = $this->currentUserEntityManager->get();

            // allow for any users
            if (in_array('!users', $item['allow'])) {
                return self::ACCESS_GRANTED;
            }

            // allow for only a group
            if (in_array('+' . $currentUserEntity->getRole(), $item['allow'])) {
                return self::ACCESS_GRANTED;
            }

            // allow for a specified user
            if (in_array('@' . $currentUserEntity->getEmail(), $item['allow'])) {
                return self::ACCESS_GRANTED;
            }

            // allow for own property
            if (in_array('!own', $item['allow']) && ($currentUserEntity->getId() === $userId)) {
                return self::ACCESS_GRANTED;
            }

            return self::ACCESS_DENIED;
        }

        return self::AUTHENTICATION_REQUIRED;
    }

}
