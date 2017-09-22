<?php

namespace Application\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\CapabilityManager;
use Application\Service\UserManager;

class CapabilityManagerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = null) {
        $userManager = $container->get(UserManager::class);
        $authenticationService = $container->get(AuthenticationService::class);
        $config = $container->get('Config');
        if (isset($config['capability_config'])) {
            $config = $config['capability_config'];
        } else {
            $config = [];
        }

        return new CapabilityManager($userManager, $authenticationService, $config);
    }

}
