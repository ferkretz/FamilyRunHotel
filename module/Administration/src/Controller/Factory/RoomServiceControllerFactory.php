<?php

namespace Administration\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Administration\Controller\RoomServiceController;
use Administration\Service\RoomServiceQueryManager;
use Administration\Service\RoomServiceManager;
use Application\Service\Localizator;

class RoomServiceControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $roomServiceQueryManager = $container->get(RoomServiceQueryManager::class);
        $roomServiceManager = $container->get(RoomServiceManager::class);
        $localizator = $container->get(Localizator::class);

        return new RoomServiceController($roomServiceQueryManager, $roomServiceManager, $localizator);
    }

}
