<?php

namespace Application\Service\User\Factory;

use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Session\SessionManager;
use Application\Service\User\CurrentUserEntityManager;
use Application\Service\User\AuthenticationManager;

class AuthenticationManagerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $authenticationService = $container->get(AuthenticationService::class);
        $sessionManager = $container->get(SessionManager::class);
        $currentUserEntityManager = $container->get(CurrentUserEntityManager::class);
        $config = $container->get('Config');
        if (isset($config['site']['accessFilters'])) {
            $config = $config['site']['accessFilters'];
        } else {
            $config = [];
        }

        return new AuthenticationManager($authenticationService, $sessionManager, $currentUserEntityManager, $config);
    }

}
