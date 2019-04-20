<?php

namespace Application\Service\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\Service\ServiceManager;

class ServiceManagerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = null) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        return new ServiceManager($entityManager);
    }

}
