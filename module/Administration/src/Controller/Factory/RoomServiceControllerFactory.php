<?php

namespace Administration\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Administration\Controller\RoomServiceController;
use Administration\Service\RoomServiceQueryManager;
use Application\Service\Localizator;
use Application\Service\RoomServiceManager;
use Application\Service\SiteOptionManager;

class RoomServiceControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $roomServiceQueryManager = $container->get(RoomServiceQueryManager::class);
        $roomServiceManager = $container->get(RoomServiceManager::class);
        $localizator = $container->get(Localizator::class);
        $optionManager = $container->get(SiteOptionManager::class);

        return new RoomServiceController($roomServiceQueryManager, $roomServiceManager, $localizator, $optionManager);
    }

}
