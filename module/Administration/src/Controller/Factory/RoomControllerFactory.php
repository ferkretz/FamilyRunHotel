<?php

namespace Administration\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Administration\Controller\RoomController;
use Administration\Service\RoomQueryManager;
use Administration\Service\RoomManager;
use Application\Service\Localizator;

class RoomControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $roomQueryManager = $container->get(RoomQueryManager::class);
        $roomManager = $container->get(RoomManager::class);
        $localizator = $container->get(Localizator::class);

        return new RoomController($roomQueryManager, $roomManager, $localizator);
    }

}
