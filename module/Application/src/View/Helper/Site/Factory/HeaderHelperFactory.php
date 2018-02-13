<?php

namespace Application\View\Helper\Site\Factory;

use Interop\Container\ContainerInterface;
use Zend\Mvc\I18n\Translator;
use Application\Service\Site\CurrentOptionValueManager;
use Application\Service\Site\SiteOptionValueManager;
use Application\View\Helper\Site\HeaderHelper;

class HeaderHelperFactory {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $translator = $container->get(Translator::class);
        $routeMatch = $container->get('Application')->getMvcEvent()->getRouteMatch();
        $currentOptionValueManager = $container->get(CurrentOptionValueManager::class);
        $siteOptionValueManager = $container->get(SiteOptionValueManager::class);

        $defaultLookData = [
            'renderHeader' => 'home',
        ];
        $lookData = $currentOptionValueManager->findOneByName('look', $defaultLookData);
        $lookRenderHeader = $lookData['renderHeader'];

        $defaultCompanyData = [
            'name' => 'Family-run Hotel',
            'i18n' => FALSE,
        ];
        $companyData = $siteOptionValueManager->findOneByName('company', $defaultCompanyData);
        if ($companyData['i18n']) {
            $companyName = $translator->translate($companyData['name']);
        } else {
            $companyName = $companyData['name'];
        }

        return new HeaderHelper($lookRenderHeader, $companyName, $routeMatch);
    }

}
