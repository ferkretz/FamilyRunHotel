<?php

namespace Profile\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\Site\CurrentOptionValueManager;
use Application\Service\User\CurrentUserEntityManager;
use Profile\Controller\SettingsController;

class SettingsControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $currentUserEntityManager = $container->get(CurrentUserEntityManager::class);
        $currentOptionValueManager = $container->get(CurrentOptionValueManager::class);

        return new SettingsController($currentUserEntityManager, $currentOptionValueManager);
    }

}
