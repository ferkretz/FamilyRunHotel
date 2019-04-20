<?php

namespace Application\View\Helper\Factory;

use Interop\Container\ContainerInterface;
use Application\Service\Option\SettingsManager;
use Application\Service\User\AccessManager;
use Application\Service\User\AuthenticationManager;
use Application\View\Helper\Navbar;

class NavbarFactory {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $accessManager = $container->get(AccessManager::class);
        $authenticationManager = $container->get(AuthenticationManager::class);
        $routeMatch = $container->get('Application')->getMvcEvent()->getRouteMatch();
        $settingsManager = $container->get(SettingsManager::class);

        if (isset($container->get('config')['navigation']['menu'])) {
            $menus = $container->get('config')['navigation']['menu'];
        } else {
            $menus = [];
        }

        return new Navbar($accessManager, $authenticationManager, $routeMatch, $settingsManager, $menus);
    }

}
