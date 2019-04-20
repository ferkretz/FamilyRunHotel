<?php

namespace Application\Service\User\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\User\AuthenticationAdapter;
use Application\Service\User\UserManager;

class AuthenticationAdapterFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = null) {
        $userManager = $container->get(UserManager::class);

        return new AuthenticationAdapter($userManager);
    }

}
