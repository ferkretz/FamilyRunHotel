<?php

namespace Application\View\Helper\Site\Factory;

use Interop\Container\ContainerInterface;
use Zend\Mvc\I18n\Translator;
use Application\Model\Site\MenuModel;
use Application\Service\Site\CurrentOptionValueManager;
use Application\Service\Site\SiteOptionValueManager;
use Application\Service\User\AuthenticationManager;
use Application\Service\User\CurrentUserEntityManager;
use Application\View\Helper\Site\NavigationBarHelper;

class NavigationBarHelperFactory {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $translator = $container->get(Translator::class);
        $authenticationManager = $container->get(AuthenticationManager::class);
        $currentUserEntityManager = $container->get(CurrentUserEntityManager::class);
        $currentOptionValueManager = $container->get(CurrentOptionValueManager::class);
        $siteOptionValueManager = $container->get(SiteOptionValueManager::class);
        $config = $container->get('Config');

        $defaultLookData = [
            'barStyle' => 'default',
        ];
        $lookData = $currentOptionValueManager->findOneByName('look', $defaultLookData);
        $lookBarStyle = $lookData['barStyle'];

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

        $menuModel = new MenuModel();
        if (isset($config['site']['navigationMenu'])) {
            $menuModel->parseMenuData($config['site']['navigationMenu']);
        }

        return new NavigationBarHelper($lookBarStyle, $companyName, $menuModel, $translator, $authenticationManager, $currentUserEntityManager);
    }

}
