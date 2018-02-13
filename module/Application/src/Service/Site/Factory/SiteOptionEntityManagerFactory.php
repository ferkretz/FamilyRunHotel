<?php

namespace Application\Service\Site\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\Site\SiteOptionEntityManager;

class SiteOptionEntityManagerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        return new SiteOptionEntityManager($entityManager);
    }

}
