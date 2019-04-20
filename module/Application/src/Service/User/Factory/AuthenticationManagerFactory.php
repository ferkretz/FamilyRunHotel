<?php

namespace Application\Service\User\Factory;

use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Session\SessionManager;
use Application\Service\User\AuthenticationManager;
use Application\Service\User\UserManager;

class AuthenticationManagerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = null) {
        $authenticationService = $container->get(AuthenticationService::class);
        $sessionManager = $container->get(SessionManager::class);
        $userManager = $container->get(UserManager::class);

        return new AuthenticationManager($authenticationService, $sessionManager, $userManager);
    }

}
