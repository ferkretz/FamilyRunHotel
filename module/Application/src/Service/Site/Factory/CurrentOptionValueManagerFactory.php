<?php

namespace Application\Service\Site\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\Site\SiteOptionEntityManager;
use Application\Service\Site\CurrentOptionValueManager;
use Application\Service\User\CurrentUserEntityManager;

class CurrentOptionValueManagerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $siteOptionEntityManager = $container->get(SiteOptionEntityManager::class);
        $currentUserEntityManager = $container->get(CurrentUserEntityManager::class);
        $config = $container->get('Config');
        $siteConfig = isset($config['site']) ? $config['site'] : [];

        return new CurrentOptionValueManager($siteOptionEntityManager, $currentUserEntityManager, $siteConfig);
    }

}
