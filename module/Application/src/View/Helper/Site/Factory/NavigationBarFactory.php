<?php

namespace Application\View\Helper\Site\Factory;

use Interop\Container\ContainerInterface;
use Application\Model\Site\MenuModel;
use Application\Service\Site\CurrentOptionValueManager;
use Application\Service\Site\SiteOptionValueManager;
use Application\Service\User\AuthenticationManager;
use Application\Service\User\CurrentUserEntityManager;
use Application\View\Helper\Site\NavigationBar;

class NavigationBarFactory {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $authenticationManager = $container->get(AuthenticationManager::class);
        $currentUserEntityManager = $container->get(CurrentUserEntityManager::class);
        $currentOptionValueManager = $container->get(CurrentOptionValueManager::class);
        $siteOptionValueManager = $container->get(SiteOptionValueManager::class);
        $config = $container->get('Config');

        $defaultLookConfig = [
            'barStyle' => 'default',
        ];
        $lookConfig = $currentOptionValueManager->findOneByName('look', $defaultLookConfig);

        $defaultCompanyConfig = [
            'name' => 'Family-run Hotel',
            'i18n' => FALSE,
        ];
        $companyConfig = $siteOptionValueManager->findOneByName('company', $defaultCompanyConfig);

        $menuModel = new MenuModel();
        if (isset($config['site']['navigationMenu'])) {
            $menuModel->parseMenuData($config['site']['navigationMenu']);
        }

        return new NavigationBar($menuModel, $authenticationManager, $currentUserEntityManager, $lookConfig, $companyConfig);
    }

}
