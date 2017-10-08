<?php

namespace Application\View\Helper\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\NavBarManager;
use Application\View\Helper\NavBar;

class NavBarFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $navBarManager = $container->get(NavBarManager::class);
        $navBarElements = $navBarManager->getDefaultNavBarElements();

        return new NavBar($navBarElements);
    }

}
