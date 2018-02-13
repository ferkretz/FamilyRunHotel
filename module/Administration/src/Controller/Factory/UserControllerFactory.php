<?php

namespace Administration\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Administration\Controller\UserController;
use Application\Service\User\UserEntityManager;

class UserControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $userEntityManager = $container->get(UserEntityManager::class);
        $config = $container->get('Config');
        if (isset($config['site']['roles'])) {
            foreach ($config['site']['roles'] as $key => $value) {
                $roles[$key] = $value['summary'];
            }
        } else {
            $roles = [];
        }

        return new UserController($userEntityManager, $roles);
    }

}
