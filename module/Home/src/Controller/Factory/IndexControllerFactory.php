<?php

namespace Home\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\Site\SiteOptionValueManager;
use Home\Controller\IndexController;

class IndexControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $siteOptionValueManager = $container->get(SiteOptionValueManager::class);

        return new IndexController($siteOptionValueManager);
    }

}
