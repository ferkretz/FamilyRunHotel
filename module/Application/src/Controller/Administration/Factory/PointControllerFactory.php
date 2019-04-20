<?php

namespace Application\Controller\Administration\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\Administration\PointController;
use Application\Service\Option\SettingsManager;
use Application\Service\Point\PointManager;
use Application\Service\Point\PointQueryManager;
use Application\Service\User\AccessManager;

class PointControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = null) {
        $settingsManager = $container->get(SettingsManager::class);
        $pointManager = $container->get(PointManager::class);
        $pointQueryManager = $container->get(PointQueryManager::class);
        $accessManager = $container->get(AccessManager::class);

        return new PointController($settingsManager, $pointManager, $pointQueryManager, $accessManager);
    }

}
