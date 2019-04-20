<?php

namespace Application\Controller\Profile\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\Profile\AccountController;
use Application\Service\User\AccessManager;
use Application\Service\User\AuthenticationManager;
use Application\Service\User\UserManager;

class AccountControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $accessManager = $container->get(AccessManager::class);
        $authenticationManager = $container->get(AuthenticationManager::class);
        $userManager = $container->get(UserManager::class);

        return new AccountController($accessManager, $authenticationManager, $userManager);
    }

}
