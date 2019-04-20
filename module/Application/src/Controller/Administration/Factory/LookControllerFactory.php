<?php

namespace Application\Controller\Administration\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\Administration\LookController;
use Application\Service\Option\SettingsManager;
use Application\Service\User\AccessManager;

class LookControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $settingsManager = $container->get(SettingsManager::class);
        $accessManager = $container->get(AccessManager::class);

        return new LookController($settingsManager, $accessManager);
    }

}
