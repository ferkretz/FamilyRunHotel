<?php

namespace Application\Service\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\Service\ServiceEntityManager;

class ServiceEntityManagerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        return new ServiceEntityManager($entityManager);
    }

}
