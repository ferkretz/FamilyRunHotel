<?php

namespace Application\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Session\SessionManager;
use Administration\Service\CapabilityManager;
use Application\Service\AuthenticationManager;

class AuthenticationManagerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $authenticationService = $container->get(AuthenticationService::class);
        $sessionManager = $container->get(SessionManager::class);
        $capabilityManager = $container->get(CapabilityManager::class);
        $config = $container->get('Config');
        if (isset($config['access_filter'])) {
            $config = $config['access_filter'];
        } else {
            $config = [];
        }

        return new AuthenticationManager($authenticationService, $sessionManager, $capabilityManager, $config);
    }

}
