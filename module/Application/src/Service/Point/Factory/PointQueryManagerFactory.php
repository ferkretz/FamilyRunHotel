<?php

namespace Application\Service\Point\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\Option\SettingsManager;
use Application\Service\Point\PointQueryManager;

class PointQueryManagerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = null) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $settingsManager = $container->get(SettingsManager::class);

        return new PointQueryManager($entityManager, $settingsManager);
    }

}
