<?php

namespace Authentication\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\UserManager;
use Authentication\Service\AuthenticationAdapter;

class AuthenticationAdapterFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $userManager = $container->get(UserManager::class);

        return new AuthenticationAdapter($userManager);
    }

}
