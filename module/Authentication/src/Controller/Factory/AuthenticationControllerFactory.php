<?php

namespace Authentication\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\User\AuthenticationManager;
use Application\Service\User\UserEntityManager;
use Authentication\Controller\AuthenticationController;

class AuthenticationControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $authenticationManager = $container->get(AuthenticationManager::class);
        $authenticationService = $container->get(AuthenticationService::class);
        $userEntityManager = $container->get(UserEntityManager::class);

        return new AuthenticationController($authenticationManager, $authenticationService, $userEntityManager);
    }

}
