<?php

namespace Authentication\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\UserManager;
use Authentication\Service\CurrentUserManager;

class CurrentUserManagerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $authenticationService = $container->get(AuthenticationService::class);
        $userManager = $container->get(UserManager::class);

        return new CurrentUserManager($authenticationService, $userManager);
    }

}
