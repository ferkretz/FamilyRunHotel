<?php

namespace Application\Controller\Administration\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\Administration\ServiceController;
use Application\Service\Option\SettingsManager;
use Application\Service\Service\ServiceManager;
use Application\Service\Service\ServiceQueryManager;
use Application\Service\User\AccessManager;

class ServiceControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = null) {
        $settingsManager = $container->get(SettingsManager::class);
        $serviceManager = $container->get(ServiceManager::class);
        $serviceQueryManager = $container->get(ServiceQueryManager::class);
        $accessManager = $container->get(AccessManager::class);

        return new ServiceController($settingsManager, $serviceManager, $serviceQueryManager, $accessManager);
    }

}
