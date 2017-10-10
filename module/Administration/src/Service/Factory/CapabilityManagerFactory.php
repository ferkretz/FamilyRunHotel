<?php

namespace Administration\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;
use Administration\Service\CapabilityManager;
use Administration\Service\UserManager;

class CapabilityManagerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $userManager = $container->get(UserManager::class);
        $authenticationService = $container->get(AuthenticationService::class);
        $config = $container->get('Config');
        $roleConfig = $config['role_config'] ?? [];

        return new CapabilityManager($userManager, $authenticationService, $roleConfig);
    }

}
