<?php

namespace Application\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\AuthenticationAdapter;
use Application\Service\UserManager;

class AuthenticationAdapterFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = null) {
        $userManager = $container->get(UserManager::class);

        return new AuthenticationAdapter($userManager);
    }

}
