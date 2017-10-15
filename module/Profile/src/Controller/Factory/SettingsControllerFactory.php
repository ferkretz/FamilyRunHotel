<?php

namespace Profile\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\SiteOptionManager;
use Application\Service\ThemeSelector;
use Authentication\Service\CurrentUserManager;
use Profile\Controller\SettingsController;

class SettingsControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $currentUserManager = $container->get(CurrentUserManager::class);
        $optionManager = $container->get(SiteOptionManager::class);
        $themeSelector = $container->get(ThemeSelector::class);

        return new SettingsController($currentUserManager, $optionManager, $themeSelector);
    }

}
