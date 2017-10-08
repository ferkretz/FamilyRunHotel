<?php

namespace Application\View\Helper\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Administration\Service\OptionManager;
use Application\View\Helper\Header;

class HeaderFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $optionManager = $container->get(OptionManager::class);

        $routeMatch = $container->get('application')->getMvcEvent()->getRouteMatch();
        if ($routeMatch == NULL) {
            return new Header($optionManager, NULL, NULL);
        }

        $controllerName = $routeMatch->getParam('controller', NULL);
        $actionName = $routeMatch->getParam('action', NULL);
        $actionName = str_replace('-', '', lcfirst(ucwords($actionName, '-')));

        return new Header($optionManager, $controllerName, $actionName);
    }

}
