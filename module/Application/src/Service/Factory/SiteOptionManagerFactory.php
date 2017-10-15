<?php

namespace Application\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\SiteOptionManager;
use Authentication\Service\CurrentUserManager;

class SiteOptionManagerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $currentUserManager = $container->get(CurrentUserManager::class);
        $config = $container->get('Config');
        $defaultSiteOptions = $config['site_options'] ?? [];

        return new SiteOptionManager($entityManager, $currentUserManager, $defaultSiteOptions);
    }

}
