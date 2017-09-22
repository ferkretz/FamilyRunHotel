<?php

namespace Application\View\Helper\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\NavManager;
use Application\Service\OptionManager;
use Application\View\Helper\NavBar;

class NavBarFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = null) {
        $navManager = $container->get(NavManager::class);
        $items = $navManager->getMenuItems();
        $optionManager = $container->get(OptionManager::class);

        return new NavBar($optionManager, $items);
    }

}
