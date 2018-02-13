<?php

namespace Application\Service\User\Factory;

use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\User\UserEntityManager;
use Application\Service\User\CurrentUserEntityManager;

class CurrentUserEntityManagerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $authenticationService = $container->get(AuthenticationService::class);
        $userEntityManager = $container->get(UserEntityManager::class);

        return new CurrentUserEntityManager($authenticationService, $userEntityManager);
    }

}
