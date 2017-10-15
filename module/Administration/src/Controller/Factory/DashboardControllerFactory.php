<?php

namespace Administration\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Administration\Controller\DashboardController;
use Application\Service\SiteOptionManager;
use Application\Service\ThemeSelector;

class DashboardControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $optionManager = $container->get(SiteOptionManager::class);
        $themeSelector = $container->get(ThemeSelector::class);

        return new DashboardController($optionManager, $themeSelector);
    }

}
