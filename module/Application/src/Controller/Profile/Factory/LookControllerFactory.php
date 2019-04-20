<?php

namespace Application\Controller\Profile\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\Profile\LookController;
use Application\Service\Option\SettingsManager;
use Application\Service\User\AccessManager;
use Application\Service\User\AuthenticationManager;

class LookControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $settingsManager = $container->get(SettingsManager::class);
        $accessManager = $container->get(AccessManager::class);
        $authenticationManager = $container->get(AuthenticationManager::class);

        return new LookController($settingsManager, $accessManager, $authenticationManager);
    }

}
