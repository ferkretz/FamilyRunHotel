<?php

namespace Application\Service\Room\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\Room\RoomEntityManager;

class RoomEntityManagerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        return new RoomEntityManager($entityManager);
    }

}
