<?php

namespace Application\Service\Picture\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\Picture\PictureEntityManager;

class PictureEntityManagerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        return new PictureEntityManager($entityManager);
    }

}
