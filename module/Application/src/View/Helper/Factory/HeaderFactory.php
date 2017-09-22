<?php

namespace Application\View\Helper\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\OptionManager;
use Application\View\Helper\Header;

class HeaderFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = null) {
        $optionManager = $container->get(OptionManager::class);
        $controller = $container->get('application')->getMvcEvent()->getRouteMatch()->getParam('controller', NULL);

        return new Header($optionManager, $controller);
    }

}
