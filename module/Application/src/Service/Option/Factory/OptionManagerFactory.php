<?php

namespace Application\Service\Option\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\Option\OptionManager;

class OptionManagerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = null) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        return new OptionManager($entityManager);
    }

}
