<?php

namespace Application\Service\User\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\User\UserManager;

class UserManagerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = null) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        return new UserManager($entityManager);
    }

}
