<?php

namespace Administration\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Administration\Controller\ServiceController;
use Application\Service\Locale\LocaleEntityManager;
use Application\Service\Service\ServiceEntityManager;
use Application\Service\Site\SiteOptionValueManager;

class ServiceControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $serviceEntityManager = $container->get(ServiceEntityManager::class);
        $localeEntityManager = $container->get(LocaleEntityManager::class);
        $siteOptionValueManager = $container->get(SiteOptionValueManager::class);

        return new ServiceController($serviceEntityManager, $localeEntityManager, $siteOptionValueManager);
    }

}
