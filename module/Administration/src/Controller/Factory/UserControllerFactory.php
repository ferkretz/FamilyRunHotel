<?php

namespace Administration\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Administration\Controller\UserController;
use Administration\Service\UserQueryManager;
use Application\Service\SiteOptionManager;
use Application\Service\UserManager;

class UserControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $userQueryManager = $container->get(UserQueryManager::class);
        $userManager = $container->get(UserManager::class);
        $optionManager = $container->get(SiteOptionManager::class);
        $config = $container->get('Config');
        if (isset($config['role_config'])) {
            foreach ($config['role_config'] as $key => $value) {
                $roles[$key] = $value['summary'];
            }
        } else {
            $roles = [];
        }

        return new UserController($userQueryManager, $userManager, $optionManager, $roles);
    }

}
