<?php

namespace Application\Controller\Index\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\User\AuthenticationManager;
use Application\Controller\Index\AuthenticationController;

class AuthenticationControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $authenticationManager = $container->get(AuthenticationManager::class);

        return new AuthenticationController($authenticationManager);
    }

}
