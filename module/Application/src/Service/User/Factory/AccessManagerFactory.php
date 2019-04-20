<?php

namespace Application\Service\User\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\User\AccessManager;
use Application\Service\User\AuthenticationManager;

class AccessManagerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = null) {
        $event = $container->get('Application')->getMvcEvent();
        $authenticationManager = $container->get(AuthenticationManager::class);
        if (isset($container->get('config')['access_manager'])) {
            $config = $container->get('config')['access_manager'];
        } else {
            throw new \UnexpectedValueException('Failed to get access manager config');
        }

        return new AccessManager($event, $authenticationManager, $config);
    }

}
