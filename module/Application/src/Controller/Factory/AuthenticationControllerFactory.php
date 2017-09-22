<?php

namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\AuthenticationController;
use Application\Service\AuthenticationManager;
use Application\Service\UserManager;

class AuthenticationControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = null) {
        $authenticationManager = $container->get(AuthenticationManager::class);
        $authenticationService = $container->get(AuthenticationService::class);
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userManager = $container->get(UserManager::class);

        return new AuthenticationController($authenticationManager, $authenticationService, $entityManager, $userManager);
    }

}
