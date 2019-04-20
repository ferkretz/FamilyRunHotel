<?php

namespace Application\Controller\Administration\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\Administration\RoomController;
use Application\Service\Option\SettingsManager;
use Application\Service\Photo\PhotoManager;
use Application\Service\Photo\PhotoQueryManager;
use Application\Service\Room\RoomManager;
use Application\Service\Room\RoomQueryManager;
use Application\Service\Service\ServiceManager;
use Application\Service\Service\ServiceQueryManager;
use Application\Service\User\AccessManager;

class RoomControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = null) {
        $settingsManager = $container->get(SettingsManager::class);
        $photoManager = $container->get(PhotoManager::class);
        $photoQueryManager = $container->get(PhotoQueryManager::class);
        $roomManager = $container->get(RoomManager::class);
        $roomQueryManager = $container->get(RoomQueryManager::class);
        $serviceManager = $container->get(ServiceManager::class);
        $serviceQueryManager = $container->get(ServiceQueryManager::class);
        $accessManager = $container->get(AccessManager::class);

        return new RoomController($settingsManager, $photoManager, $photoQueryManager, $roomManager, $roomQueryManager, $serviceManager, $serviceQueryManager, $accessManager);
    }

}
