<?php

namespace Administration\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Administration\Controller\DashboardController;
use Administration\Service\OptionManager;

class DashboardControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $optionManager = $container->get(OptionManager::class);

        return new DashboardController($optionManager);
    }

}
