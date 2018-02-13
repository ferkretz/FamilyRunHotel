<?php

namespace Application\Service\User\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\User\AuthenticationAdapter;
use Application\Service\User\UserEntityManager;

class AuthenticationAdapterFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $userEntityManager = $container->get(UserEntityManager::class);

        return new AuthenticationAdapter($userEntityManager);
    }

}
