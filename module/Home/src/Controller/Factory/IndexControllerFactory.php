<?php

namespace Home\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Model\NavBarData;
use Application\Service\SiteOptionManager;
use Home\Controller\IndexController;

class IndexControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $navBarData = $container->get(NavBarData::class);
        $optionManager = $container->get(SiteOptionManager::class);
        
        return new IndexController($optionManager, $navBarData);
    }

}
