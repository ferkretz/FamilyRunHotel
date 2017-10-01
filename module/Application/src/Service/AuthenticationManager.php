<?php

namespace Application\Service;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Result;
use Zend\Session\SessionManager;
use Administration\Service\CapabilityManager;

class AuthenticationManager {

    // Constants returned by the access filter.
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
     * Capability manager.
     * @var CapabilityManager
     */
    protected $capabilityManager;

    /**
     * Contents of the 'access_filter' config key.
     * @var array
     */
    protected $config;

    public function __construct(AuthenticationService $authenticationService,
                                SessionManager $sessionManager,
                                CapabilityManager $capabilityManager,
                                $config) {
        $this->authenticationService = $authenticationService;
        $this->sessionManager = $sessionManager;
        $this->capabilityManager = $capabilityManager;
        $this->config = $config;
    }

    public function login($email,
                          $password,
                          $rememberMe) {
        if ($this->authenticationService->getIdentity() != null) {
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
        if ($this->authenticationService->getIdentity() == null) {
            throw new \Exception('The user is not logged in');
        }

        $this->authenticationService->clearIdentity();
    }

    public function filterAccess($controllerName,
                                 $actionName) {
        $mode = isset($this->config['options']['mode']) ? $this->config['options']['mode'] : 'restrictive';
        if ($mode != 'restrictive' && $mode != 'permissive') {
            throw new \Exception('Invalid access filter mode (expected either restrictive or permissive mode');
        }

        if (isset($this->config['controllers'][$controllerName])) {
            $items = $this->config['controllers'][$controllerName];

            foreach ($items as $item) {
                $actionList = $item['actions'];
                $allow = $item['allow'];
                $checkOwner = isset($item['check_owner']) ? TRUE : FALSE;

                if (is_array($actionList) && in_array($actionName, $actionList) ||
                        $actionList == '*') {
                    if ($allow == '*') {
                        return self::ACCESS_GRANTED;
                    } else if (!$this->authenticationService->hasIdentity()) {
                        return self::AUTHENTICATION_REQUIRED;
                    }

                    if ($allow == '@') {
                        return self::ACCESS_GRANTED;
                    } else if (substr($allow, 0, 1) == '@') {
                        $identity = substr($allow, 1);
                        if ($this->authenticationService->getIdentity() === $identity) {
                            return self::ACCESS_GRANTED;
                        } else {
                            return self::ACCESS_DENIED;
                        }
                    } else if (substr($allow, 0, 1) == '+') {
                        $capability = substr($allow, 1);
                        if ($this->capabilityManager->currentUserCan($capability)) {
                            return self::ACCESS_GRANTED;
                        } else {
                            return self::ACCESS_DENIED;
                        }
                    } else {
                        throw new \Exception('Unexpected value for "allow" - expected ' .
                        'either "?", "@", "@identity" or "+permission"');
                    }
                }
            }
        }

        if ($mode == 'restrictive') {
            if (!$this->authenticationService->hasIdentity()) {
                return self::AUTHENTICATION_REQUIRED;
            } else {
                return self::ACCESS_DENIED;
            }
        }

        return self::ACCESS_GRANTED;
    }

}
