<?php

namespace Application\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Administration\Service\UserManager;
use Application\Service\AuthenticationAdapter;

class AuthenticationAdapterFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = null) {
        $userManager = $container->get(UserManager::class);

        return new AuthenticationAdapter($userManager);
    }

}
