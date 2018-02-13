<?php

namespace Administration\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Administration\Controller\DashboardController;
use Application\Service\Site\SiteOptionEntityManager;
use Application\Service\Site\SiteOptionValueManager;

class DashboardControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $siteOptionValueManager = $container->get(SiteOptionValueManager::class);
        $siteOptionEntityManager = $container->get(SiteOptionEntityManager::class);

        return new DashboardController($siteOptionValueManager, $siteOptionEntityManager);
    }

}
