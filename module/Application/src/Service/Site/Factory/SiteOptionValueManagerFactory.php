<?php

namespace Application\Service\Site\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\Site\SiteOptionEntityManager;
use Application\Service\Site\SiteOptionValueManager;

class SiteOptionValueManagerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $siteOptionEntityManager = $container->get(SiteOptionEntityManager::class);
        $config = $container->get('Config');
        $siteConfig = isset($config['site']) ? $config['site'] : [];

        return new SiteOptionValueManager($siteOptionEntityManager, $siteConfig);
    }

}
