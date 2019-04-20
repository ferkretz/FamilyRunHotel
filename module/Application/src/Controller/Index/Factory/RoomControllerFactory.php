<?php

namespace Application\Controller\Index\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\Index\RoomController;
use Application\Service\Option\SettingsManager;
use Application\Service\Room\RoomManager;
use Application\Service\Room\RoomQueryManager;
use Application\Service\User\AccessManager;

class RoomControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = null) {
        $settingsManager = $container->get(SettingsManager::class);
        $roomManager = $container->get(RoomManager::class);
        $roomQueryManager = $container->get(RoomQueryManager::class);
        $accessManager = $container->get(AccessManager::class);

        return new RoomController($settingsManager, $roomManager, $roomQueryManager, $accessManager);
    }

}
