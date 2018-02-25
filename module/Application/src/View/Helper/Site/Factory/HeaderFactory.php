<?php

namespace Application\View\Helper\Site\Factory;

use Interop\Container\ContainerInterface;
use Application\Service\Site\CurrentOptionValueManager;
use Application\Service\Site\SiteOptionValueManager;
use Application\View\Helper\Site\Header;

class HeaderFactory {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $routeMatch = $container->get('Application')->getMvcEvent()->getRouteMatch();
        $currentOptionValueManager = $container->get(CurrentOptionValueManager::class);
        $siteOptionValueManager = $container->get(SiteOptionValueManager::class);

        $defaultLookConfig = [
            'renderHeader' => 'home',
        ];
        $lookConfig = $currentOptionValueManager->findOneByName('look', $defaultLookConfig);

        $defaultCompanyConfig = [
            'name' => 'Family-run Hotel',
            'i18n' => FALSE,
        ];
        $companyConfig = $siteOptionValueManager->findOneByName('company', $defaultCompanyConfig);

        return new Header($routeMatch, $lookConfig, $companyConfig);
    }

}
