<?php

namespace Application\Controller\Administration\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\Administration\UserController;
use Application\Service\Option\SettingsManager;
use Application\Service\User\AccessManager;
use Application\Service\User\UserManager;
use Application\Service\User\UserQueryManager;

class UserControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = null) {
        $settingsManager = $container->get(SettingsManager::class);
        $accessManager = $container->get(AccessManager::class);
        $userManager = $container->get(UserManager::class);
        $userQueryManager = $container->get(UserQueryManager::class);

        return new UserController($settingsManager, $accessManager, $userManager, $userQueryManager);
    }

}
