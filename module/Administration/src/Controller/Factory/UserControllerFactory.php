<?php

namespace Administration\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Administration\Controller\UserController;
use Administration\Service\UserQueryManager;
use Administration\Service\UserManager;

class UserControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = null) {
        $userQueryManager = $container->get(UserQueryManager::class);
        $userManager = $container->get(UserManager::class);

        return new UserController($userQueryManager, $userManager);
    }

}
