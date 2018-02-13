<?php

namespace Application\Service\User\Factory;

use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Session\SessionManager;
use Application\Service\User\AuthenticationAdapter;

class AuthenticationServiceFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $sessionManager = $container->get(SessionManager::class);
        $authenticationStorage = new SessionStorage('Zend_Auth', 'session', $sessionManager);
        $authenticationAdapter = $container->get(AuthenticationAdapter::class);

        return new AuthenticationService($authenticationStorage, $authenticationAdapter);
    }

}
